<?php
// This file is part of PHP implementation of GroupAL
// http://sourceforge.net/projects/groupal/
//
// GroupAL is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// GroupAL implementations are distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with GroupAL. If not, see <http://www.gnu.org/licenses/>.
//
//  This code CAN be used as a code-base in Moodle
// (e.g. for moodle-mod_groupformation). Then put this code in a folder
// <moodle>\lib\groupal
/**
 * This class contains values based on users characteristics and skills
 *
 * @author Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @license http://www.gnu.org/copyleft/lgpl.html GNU LGPL v3 or later
 */
require_once($CFG->dirroot . "/lib/groupal/classes/criteria/criterion.php");
require_once($CFG->dirroot . "/lib/groupal/classes/criteria/criterion_weight.php");

class lib_groupal_specific_criterion extends lib_groupal_criterion {


    /**
     * lib_groupal_specific_criterion constructor.
     * @param $name
     * @param $valueArray
     * @param $minVal
     * @param $maxVal
     * @param $isHomo
     * @param $weight
     */
    public function __construct($name, $valueArray, $minVal, $maxVal, $isHomo, $weight) {
        $this->setName($name);
        $this->setMinValue($minVal);
        $this->setMaxValue($maxVal);
        $this->setValues($valueArray);
        $this->setIsHomogeneous($isHomo);
        lib_groupal_criterion_weight::addIfNotAllreadyExist($name, $weight);
    }
}