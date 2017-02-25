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
 * Prints a particular instance of groupformation
 *
 * @package mod_groupformation
 * @author Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/locallib.php');

require_once($CFG->dirroot . '/mod/groupformation/classes/util/test_user_generator.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/util/xml_loader.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/util/util.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/util/define_file.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/storage_manager.php');

// Read URL params.
$id = optional_param('id', 0, PARAM_INT);
$doshow = optional_param('do_show', 'analysis', PARAM_TEXT);

$runjob = optional_param('runjob', false, PARAM_BOOL);
$createusers = optional_param('create_users', 0, PARAM_INT);
$createanswers = optional_param('create_answers', false, PARAM_BOOL);
$randomanswers = optional_param('random_answers', false, PARAM_BOOL);
$deleteusers = optional_param('delete_users', false, PARAM_BOOL);
$resetjob = optional_param('reset_job', false, PARAM_BOOL);
$fixanswers = optional_param('fix_answers', false, PARAM_BOOL);

// Import jQuery and js file.
groupformation_add_jquery($PAGE, 'survey_functions.js');

// Determine instances of course module, course, groupformation.
groupformation_determine_instance($id, $cm, $course, $groupformation);

// Require user login if not already logged in.
require_login($course, true, $cm);

// Get useful stuff.
$context = context_module::instance($cm->id);
$userid = $USER->id;

if (!has_capability('mod/groupformation:editsettings', $context)) {
    $return = new moodle_url ('/mod/groupformation/view.php', array(
        'id' => $id, 'do_show' => 'view'));
    redirect($return->out());
} else {
    $currenttab = $doshow;
}


// Log access to page.
groupformation_info($USER->id, $groupformation->id, '<view_teacher_overview>');

// Set PAGE config.
$PAGE->set_url('/mod/groupformation/analysis_view.php', array(
    'id' => $cm->id, 'do_show' => $doshow));
$PAGE->set_title(format_string($groupformation->name));
$PAGE->set_heading(format_string($course->fullname));

require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/job_manager.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/controller/analysis_controller.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/grouping/participant_parser.php');

if ($CFG->debug === 32767 && $resetjob) {
    global $DB;

    $DB->delete_records('groupformation_jobs', array('groupformationid' => $groupformation->id));
}

if ($CFG->debug === 32767 && $runjob) {
    $jm = new mod_groupformation_job_manager ();
    $job = null;

    $job = $jm::get_job($groupformation->id);

    if (!is_null($job)) {
        $result = $jm::do_groupal($job);
        xdebug_var_dump($result);
        // $saved = $jm::save_result($job,$result);
    }
}

if ($CFG->debug === 32767) {
    $cqt = new mod_groupformation_test_user_generator ($cm);

    if ($deleteusers) {
        $cqt->delete_test_users($groupformation->id);
        $return = new moodle_url ('/mod/groupformation/analysis_view.php', array(
            'id' => $id, 'do_show' => 'analysis'));
        redirect($return->out());
    }
    if ($createusers > 0) {
        $cqt->create_test_users($createusers, $groupformation->id, $createanswers, $randomanswers);
        $return = new moodle_url ('/mod/groupformation/analysis_view.php', array(
            'id' => $id, 'do_show' => 'analysis'));
        redirect($return->out());
    }
}

$controller = new mod_groupformation_analysis_controller ($groupformation->id, $cm);

/* ---- Code for fixing Answers can be removed after 15-09-2016 ---- */
if ($CFG->debug === 32767 && $fixanswers) {
    $controller->fix_answers();
    echo '<div class="alert">Answers fixed - do not repeat this action!</div>';
    $return = new moodle_url ('/mod/groupformation/analysis_view.php', array(
        'id' => $id, 'do_show' => 'analysis'));
    redirect($return->out());
}

if ((data_submitted()) && confirm_sesskey()) {
    $switcher = optional_param('questionnaire_switcher', null, PARAM_INT);

    if (isset($switcher)) {
        $controller->trigger_questionnaire($switcher);
    }
    $return = new moodle_url ('/mod/groupformation/analysis_view.php', array(
        'id' => $id, 'do_show' => 'analysis'));
    redirect($return->out());
}


echo $OUTPUT->header();

// Print the tabs.
require('tabs.php');


if (groupformation_get_current_questionnaire_version() > $store->get_version()) {
    echo '<div class="alert">' . get_string('questionnaire_outdated', 'groupformation') . '</div>';
}
if ($store->is_archived() && has_capability('mod/groupformation:editsettings', $context)) {
    echo '<div class="alert" id="commited_view">' . get_string('archived_activity_admin', 'groupformation') . '</div>';
} else {
    echo '<form action="' . htmlspecialchars($_SERVER ["PHP_SELF"]) . '" method="post" autocomplete="off">';

    echo '<input type="hidden" name="id" value="' . $id . '"/>';
    echo '<input type="hidden" name="sesskey" value="' . sesskey() . '" />';

    echo $controller->display();

    echo '</form>';
}

echo $OUTPUT->footer();
