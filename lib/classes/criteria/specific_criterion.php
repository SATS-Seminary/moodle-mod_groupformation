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
 * This class contains values based on users characteristics and skills
 *
 * @author Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @license http://www.gnu.org/copyleft/lgpl.html GNU LGPL v3 or later
 */
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/criteria/criterion.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/criteria/criterion_weight.php");

class lib_groupal_specific_criterion extends lib_groupal_criterion {


    /**
     * lib_groupal_specific_criterion constructor.
     * @param $name
     * @param $valuearray
     * @param $minval
     * @param $maxval
     * @param $ishomo
     * @param $weight
     */
    public function __construct($name, $valuearray, $minval, $maxval, $ishomo, $weight) {
        $this->set_name($name);
        $this->set_min_value($minval);
        $this->set_max_value($maxval);
        $this->set_values($valuearray);
        $this->set_homogeneous($ishomo);
        lib_groupal_criterion_weight::add_if_not_allready_exist($name, $weight);
    }
}