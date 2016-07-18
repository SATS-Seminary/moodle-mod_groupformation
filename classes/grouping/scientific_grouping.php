<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Scientific grouping interface
 *
 * @package mod_groupformation
 * @author Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!defined('MOODLE_INTERNAL')) {
    die ('Direct access to this script is forbidden.');
}

require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/storage_manager.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/user_manager.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/grouping/participant_parser.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/groups_manager.php');
require_once($CFG->dirroot . '/mod/groupformation/lib.php');
require_once($CFG->dirroot . '/mod/groupformation/locallib.php');

require_once($CFG->dirroot . '/mod/groupformation/lib/classes/criteria/specific_criterion.php');
require_once($CFG->dirroot . '/mod/groupformation/lib/classes/participant.php');
require_once($CFG->dirroot . '/mod/groupformation/lib/classes/matchers/group_centric_matcher.php');
require_once($CFG->dirroot . '/mod/groupformation/lib/classes/algorithms/basic_algorithm.php');
require_once($CFG->dirroot . '/mod/groupformation/lib/classes/algorithms/random_algorithm.php');
require_once($CFG->dirroot . '/mod/groupformation/lib/classes/algorithms/topic_algorithm.php');
require_once($CFG->dirroot . '/mod/groupformation/lib/classes/optimizers/optimizer.php');
require_once($CFG->dirroot . '/mod/groupformation/lib/classes/xml_writers/participant_writer.php');
require_once($CFG->dirroot . '/mod/groupformation/lib/classes/xml_writers/cohort_writer.php');

class mod_groupformation_scientific_grouping {

    private $groupformationid;
    private $usermanager;
    private $store;
    private $groupsmanager;
    private $criterioncalculator;

    /**
     * mod_groupformation_job_manager constructor.
     * @param $groupformationid
     */
    public function __construct($groupformationid) {
        $this->groupformationid = $groupformationid;
        $this->usermanager = new mod_groupformation_user_manager($groupformationid);
        $this->store = new mod_groupformation_storage_manager($groupformationid);
        $this->groupsmanager = new mod_groupformation_groups_manager($groupformationid);
        $this->criterioncalculator = new mod_groupformation_criterion_calculator($groupformationid);
        $this->participantparser = new mod_groupformation_participant_parser($groupformationid);
    }

    /**
     * Returns configured participants with either two (GH and EX), one (GH or EX) or no criteria
     *
     * @param $participants
     * @param $configuration
     * @return mixed
     */
    public function configure_participants($participants, $configuration) {

        foreach ($participants as $participant) {
            $criteria = $participant->criteria;
            $configured_criteria = array();
            if (count($configuration) == 0) {
                $configured_criteria = null;
            } else {
                for ($j = 0; $j < count($criteria); $j++) {
                    $criterion = $criteria[$j];
                    if (array_key_exists($criterion->getName(), $configuration)) {
                        $mode = $configuration[$criterion->getName()];
                        if (is_null($mode)) {
                            $criterion = null;
                        } else {
                            $criterion->setIsHomogeneous($mode);
                        }
                    }else{
                        $criterion = null;
                    }

                    if (!is_null($criterion)) {
                        $configured_criteria[] = $criterion;
                    }
                }
            }
            $participant->criteria = $configured_criteria;
        }

        return $participants;
    }

    /**
     * Scientific division of users and creation of participants
     *
     * @param $users Two parted array - first part is all groupal users, second part are all random users
     */
    public function run_grouping($users) {

        $configurations = array(
            "mrand:0;ex:1;gh:1" => array('big5_extraversion' => true, 'big5_gewissenhaftigkeit' => true),
            "mrand:0;ex:1;gh:0" => array('big5_extraversion' => true, 'big5_gewissenhaftigkeit' => false),
            "mrand:0;ex:0;gh:0" => array('big5_extraversion' => false, 'big5_gewissenhaftigkeit' => false),
            "mrand:0;ex:0;gh:1" => array('big5_extraversion' => false, 'big5_gewissenhaftigkeit' => true),
            "mrand:0;ex:1;gh:_" => array('big5_extraversion' => true, 'big5_gewissenhaftigkeit' => null),
            "mrand:0;ex:0;gh:_" => array('big5_extraversion' => false, 'big5_gewissenhaftigkeit' => null),
            "mrand:0;ex:_;gh:1" => array('big5_extraversion' => null, 'big5_gewissenhaftigkeit' => true),
            "mrand:0;ex:_;gh:0" => array('big5_extraversion' => null, 'big5_gewissenhaftigkeit' => false),
            "mrand:1;ex:_;gh:_" => array(),
        );

        $configurationkeys = array_keys($configurations);

        $numberofslices = count($configurationkeys);

        $groupsizes = mod_groupformation_job_manager::determine_group_size($users,$this->store);

        if (count($users[0])<$numberofslices){
            return array();
        }

        // divide users into n slices
        $slices = $this->slicing($users[0], $numberofslices);

        $cohorts = array();

        for ($i = 0; $i < $numberofslices; $i++) {
            $slice = $slices[$i];

            $configurationkey = $configurationkeys[$i];
            $configuration = $configurations[$configurationkey];

            $raw_participants = $this->participantparser->build_participants($slice);

            $participants = $this->configure_participants($raw_participants, $configuration);

            $cohorts[$configurationkey] = $this->build_cohort($participants, $groupsizes[0], $configurationkey);
        }

        // Handle all users with incomplete or no questionnaire submission
        $randomparticipantskey = "rand:1;mrand:_;ex:_;gh:_";

        $randomparticipants = $this->participantparser->build_empty_participants($users[1]);
        $randomcohort = $this->build_cohort($randomparticipants, $groupsizes[1],$randomparticipantskey);

        $cohorts[$randomparticipantskey] = $randomcohort;

        return $cohorts;
    }

    /**
     * Handles grouping based on algorithms determined by configuration key
     *
     * @param $users
     * @param $groupsize
     * @param $configurationkey
     * @return lib_groupal_cohort
     */
    public function build_cohort($users, $groupsize, $configurationkey) {
        if (strpos($configurationkey, "rand:1") !== false) {
            // Random
            return $this->build_random_cohort($users, $groupsize);
        } else if (strpos($configurationkey, "rand:0") !== false) {
            // Not Random
            return $this->build_groupal_cohort($users, $groupsize);
        }
    }

    /**
     * Handles grouping based on the random algorithm
     *
     * @param $users
     * @param $groupsize
     * @return lib_groupal_cohort
     */
    public function build_random_cohort($users, $groupsize) {
        $gfra = new lib_groupal_random_algorithm ($users, $groupsize);

        return $gfra->do_one_formation();
    }

    /**
     * Handles grouping based on the groupal algorithm
     *
     * @param $users
     * @param $groupsize
     * @return lib_groupal_cohort
     */
    public function build_groupal_cohort($users, $groupsize) {
        // Choose matcher.
        $matcher = new lib_groupal_group_centric_matcher ();

        $gfa = new lib_groupal_basic_algorithm ($users, $matcher, $groupsize);
        return $gfa->do_one_formation();
    }

    /**
     * Slices the array of users into a specific number of almost even sized arrays of users
     *
     * @param $users
     * @param $numberofslices
     * @return array
     */
    public function slicing($users, $numberofslices) {
        // TODO
        // shuffle($users);
        $slices = array();

        for ($i = 0; $i < count($users); $i++) {
            if ($i < $numberofslices) {
                $slices[$i] = array();
            }
            $m = $i % $numberofslices;
            $slices[$m][] = $users[$i];
        }

        return $slices;
    }

}