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
 * Define file for questionnaires
 *
 * @package mod_groupformation
 * @author Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!defined('MOODLE_INTERNAL')) {
    die ('Direct access to this script is forbidden.');
}

class mod_groupformation_data {

    private $scenarios = array(
            1 => 'projectteams',
            2 => 'homeworkgroups',
            3 => 'presentationgroups',
    );

    private $criteria = array(
            "big5" => array(
                    "category" => "character",
                    "scenarios" => array(1, 2),
                    "evaluation" => true,
                    "labels" => array(
                            "extraversion" => array(
                                    "scenarios" => array(1 => false, 2 => false),
                                    "evaluation" => true,
                                    "questionids" => array(12, -1, 13, 14, -15, 16, -17, 6),
                                    "significant_id_only" => false,
                                    "cutoffs" => array(0.313169217, 0.776242547),
                            ),
                            "gewissenhaftigkeit" => array(
                                    "scenarios" => array(1 => true, 2 => true),
                                    "evaluation" => true,
                                    "questionids" => array(8, -32, 33, -34, -35, 21, 22, 23, -24),
                                    "significant_id_only" => false,
                                    "cutoffs" => array(0.456596974, 0.831246163),
                            ),
                            "vertraeglichkeit" => array(
                                    "scenarios" => array(1 => true, 2 => true),
                                    "evaluation" => true,
                                    "questionids" => array(-7, 2, -25, -26),
                                    "significant_id_only" => false,
                                    "cutoffs" => array(0.492136484, 0.799889659),
                            ),
                            "neurotizismus" => array(
                                    "scenarios" => array(1 => false, 2 => false),
                                    "evaluation" => true,
                                    "questionids" => array(27, -4, 28, 9),
                                    "significant_id_only" => false,
                                    "cutoffs" => array(0.195135503, 0.602511556),
                            ),
                            "offenheit" => array(
                                    "scenarios" => array(1 => false, 2 => false),
                                    "evaluation" => true,
                                    "questionids" => array(29, 30, 10, 31, -5),
                                    "significant_id_only" => false,
                                    "cutoffs" => array(0.348454964, 0.829192095),
                            ),
                    ),
            ),
            "fam" => array(
                    "category" => "motivation",
                    "scenarios" => array(1),
                    "evaluation" => true,
                    "labels" => array(
                            "herausforderung" => array(
                                    "scenarios" => array(1 => false),
                                    "evaluation" => true,
                                    "questionids" => array(6, 8, 10, 15, 17),
                                    "significant_id_only" => false,
                                    "cutoffs" => array(0.518934813, 0.830866774),
                            ),
                            "interesse" => array(
                                    "scenarios" => array(1 => false),
                                    "evaluation" => true,
                                    "questionids" => array(1, 4, 7, 11),
                                    "significant_id_only" => false,
                                    "cutoffs" => array(0.439861739, 0.751249372),
                            ),
                            "erfolgswahrscheinlichkeit" => array(
                                    "scenarios" => array(1 => false),
                                    "evaluation" => true,
                                    "questionids" => array(2, 3, 13, 14),
                                    "significant_id_only" => false,
                                    "cutoffs" => array(0.314297404, 0.511297834),
                            ),
                            "misserfolgsbefuerchtung" => array(
                                    "scenarios" => array(1 => false),
                                    "evaluation" => true,
                                    "questionids" => array(5, 9, 12, 16, 18),
                                    "significant_id_only" => false,
                                    "cutoffs" => array(0.186185044, 0.601275274),
                            ),
                    ),
            ),
            "learning" => array(
                    "category" => "learning",
                    "scenarios" => array(2),
                    "evaluation" => false,
                    "labels" => array(
                            "konkreteerfahrung" => array(
                                    "scenarios" => array(2 => false),
                                    "evaluation" => false,
                                    "questionids" => array(1, 5, 11, 14, 20, 22),
                                    "significant_id_only" => false,
                                    "cutoffs" => null,
                            ),
                            "aktivesexperimentieren" => array(
                                    "scenarios" => array(2 => false),
                                    "evaluation" => false,
                                    "questionids" => array(2, 8, 10, 16, 17, 23),
                                    "significant_id_only" => false,
                                    "cutoffs" => null,
                            ),
                            "reflektiertebeobachtung" => array(
                                    "scenarios" => array(2 => false),
                                    "evaluation" => false,
                                    "questionids" => array(3, 6, 9, 13, 19, 21),
                                    "significant_id_only" => false,
                                    "cutoffs" => null,
                            ),
                            "abstraktebegriffsbildung" => array(
                                    "scenarios" => array(2 => false),
                                    "evaluation" => false,
                                    "questionids" => array(4, 7, 12, 15, 18, 24),
                                    "significant_id_only" => false,
                                    "cutoffs" => null,
                            ),
                    ),
            ),
            "general" => array(
                    "category" => "general",
                    "scenarios" => array(1, 2),
                    "evaluation" => false,
                    "labels" => array(
                            "language" => array(
                                    "scenarios" => array(1 => true, 2 => true),
                                    "evaluation" => false,
                                    "questionids" => array(1),
                                    "significant_id_only" => false,
                                    "cutoffs" => null,
                            ),
                    ),
            ),
            "grade" => array(
                    "category" => "grade",
                    "scenarios" => array(1, 2),
                    "evaluation" => false,
                    "labels" => array(
                            "one" => array(
                                    "scenarios" => array(1 => true, 2 => false),
                                    "evaluation" => false,
                                    "questionids" => array(1, 2, 3),
                                    "significant_id_only" => true,
                                    "cutoffs" => null,
                            ),
                    ),
            ),
            "points" => array(
                    "category" => "points",
                    "scenarios" => array(1, 2),
                    "evaluation" => false,
                    "labels" => array(
                            "one" => array(
                                    "scenarios" => array(1 => true, 2 => false),
                                    "evaluation" => false,
                                    "questionids" => array(1, 2, 3),
                                    "significant_id_only" => true,
                                    "cutoffs" => null,
                            ),
                    ),
            ),
            "team" => array(
                    "category" => "team",
                    "scenarios" => array(1, 2),
                    "evaluation" => false,
                    "labels" => array(
                            "one" => array(
                                    "scenarios" => array(1 => true, 2 => true),
                                    "evaluation" => false,
                                    "questionids" => array(14, 15, 16),
                                    "significant_id_only" => false,
                                    "cutoffs" => null,
                            ),
                    ),
            ),
            "knowledge" => array(
                    "category" => "knowledge",
                    "scenarios" => array(),
                    "evaluation" => false,
                    "labels" => array(
                            "one" => array(
                                    "scenarios" => array(1 => true),
                                    "evaluation" => false,
                                    "questionids" => null,
                                    "significant_id_only" => false,
                                    "separate_dimensions" => false,
                                    "cutoffs" => null,
                            ),
                            "two" => array(
                                    "scenarios" => array(1 => false, 2 => false),
                                    "evaluation" => false,
                                    "questionids" => null,
                                    "significant_id_only" => false,
                                    "separate_dimensions" => true,
                                    "cutoffs" => null,
                            ),
                    ),
            ),
    );

    private $categorysets = array(
            '1' => array(
                    'general',
                    'grade',
                    'points',
                    'character',
                    'motivation',
                    'team',
                    'srl',
                    'sellmo',
                    'self',
            ),
            '2' => array(
                    'general',
                    'grade',
                    'points',
                    'character',
                    'learning',
                    'team',
                    'srl',
                    'sellmo',
                    'self',
            ),
            '3' => array(
                    'topic',
            ),
    );

    private $mathprepcoursemode = false;
    private $allanswersrequired = false;

    /**
     * Returns whether this instance is running in math prep course mode;
     * default should be false, since math prep course mode is only for research
     *
     * @return bool
     */
    public function is_math_prep_course_mode() {
        return $this->mathprepcoursemode;
    }

    /**
     * Returns scenario name
     *
     * @param int $scenario
     * @return string
     */
    public function get_scenario_name($scenario) {
        return $this->scenarios [$scenario];
    }

    /**
     * Returns extra labels for criteria like fam, learning, big5_xxx
     *
     * @param $label
     * @return array
     */
    public function get_extra_labels($label) {
        if (array_key_exists($label, $this->criteria)) {
            return array_keys($this->criteria[$label]);
        } else {
            return array();
        }
    }

    /**
     * Returns category set
     *
     * @param int $scenario
     * @return array
     */
    public function get_category_set($scenario) {
        return $this->categorysets [$scenario];
    }

    /**
     * Returns label set
     *
     * @param int $scenario
     * @return string
     */
    public function get_label_set($scenario) {
        $labels = array();
        foreach ($this->criteria as $label => $criterion) {
            $scenarios = $criterion["scenarios"];
            if (in_array($scenario, $scenarios)) {
                $labels[] = $label;
            }
        }

        return $labels;
    }

    /**
     * Returns critetion specification
     *
     * @param $name
     * @return mixed
     */
    public function get_criterion_specification($name = null) {
        if (is_null($name)) {
            return $this->criteria;
        }
        if (array_key_exists($name, $this->criteria)) {
            return $this->criteria[$name];
        } else {
            return null;
        }
    }

    /**
     * Returns whether a participant code is required or not
     *
     * @return bool
     */
    public function ask_for_participant_code() {
        $configvalue = get_config('groupformation', 'participant_code');
        if (!is_null($configvalue)) {
            return $configvalue;
        }
        return false;
    }

    /**
     * Returns whether import export is enabled or not
     *
     * @return bool
     */
    public function import_export_enabled() {
        $configvalue = get_config('groupformation', 'import_export');
        if (!is_null($configvalue)) {
            return $configvalue;
        }
        return true;
    }

    public function all_answers_required() {
        return $this->allanswersrequired;
    }
}
