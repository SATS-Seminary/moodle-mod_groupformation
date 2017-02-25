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
 * This class contains an implementation of a topic-based group formation algorithm
 *
 * @author Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @license http://www.gnu.org/copyleft/lgpl.html GNU LGPL v3 or later
 */
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/algorithms/ialgorithm.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/group.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/cohorts/topic_cohort.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/participant.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/topics_solver/choicedata.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/topics_solver/rating_for_topic.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/topics_solver/edmonds-karp.php");

class mod_groupformation_topic_algorithm implements mod_groupformation_ialgorithm {

    /** @var array This array contains the ratings of all participants */
    public $ratings = array();

    /** @var array This array contains all topics */
    public $topics = array();

    /** @var array This array contains all participants which need to be matched */
    public $participants = array();

    /** @var int This is the number of participants */
    public $participantscount;

    /** @var mod_groupformation_cohort This is the resulting cohort */
    public $cohort;

    /**
     * mod_groupformation_topic_algorithm constructor.
     *
     * @param $topics
     * @param $participants
     */
    public function __construct($topics, $participants) {

        foreach ($participants as $p) {
            $this->participants[$p->id] = clone($p);
        }

        $this->participantscount = count($participants);

        foreach ($topics as $key => $value) {
            $this->topics[] = new mod_groupformation_choicedata($key, $value);
        }

        $this->ratings = $this->get_ratings_from_participants();

    }

    /**
     * The main method to call for getting a formation "run" (this takes a while)
     * Uses the global set matcher to assign evry not yet matched participant to a group
     *
     * @return mod_groupformation_cohort
     */
    public function do_one_formation() {
        // Run algorithm.
        $distributor = new mod_groupformation_solver_edmonds_karp();
        $results = $distributor->distribute_users($this->ratings, $this->topics, $this->participantscount);
        $groups = array();
        var_dump($results);
        foreach ($results as $topicid => $participantsids) {
            $group = new mod_groupformation_group($topicid + 1);
            foreach ($participantsids as $id) {
                $p = $this->participants[$id];
                $group->add_participant($p, true);
            }
            $groups[$topicid] = $group;
        }

        $this->cohort = new mod_groupformation_topic_cohort(count($groups), $groups);
        $this->cohort->whichmatcherused = get_class($this);
        return $this->cohort;
    }

    /**
     * Returns all ratings for active choices
     *
     * @return array
     */
    private function get_ratings_from_participants() {

        $ratingsarray = array();

        // TODO Participant with just topics as criterions? can the values of criterion be empty?
        foreach (array_values($this->participants) as $user) {
            $currentuserid = $user->id;
            foreach ($user->criteria as $cr) {
                if ($cr->get_name() == 'topic') {
                    $ratings = $cr->get_values();
                    foreach ($ratings as $choiceid => $rating) {
                        $ratingsarray[] = new mod_groupformation_rating_for_topic($choiceid, $currentuserid, $rating);
                    }
                }
            }
        }

        return $ratingsarray;  // Array with all ratings.

    }

}