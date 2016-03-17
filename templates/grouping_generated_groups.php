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
 * @package mod_groupformation
 * @author Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
?>

<?php foreach ($this->_ as $entry) { ?>

    <div class="grid bottom_stripe">
    <div class="col_m_75">
        <div class="group_params"><b><?php echo get_string('name_by_group', 'groupformation'); ?></b> <?php echo $entry['groupname']; ?></div><br>
        <?php if (strlen($entry['topic'])>0){ ?>
            <div class="group_params"><b><?php echo get_string('topic', 'groupformation').": "; ?></b> <?php echo $entry['topic']; ?></div>
        <?php }?>
    </div>
<!--        <div class="col_s_50">--><?php //echo get_string('name_by_group', 'groupformation'); ?>
<!--            <b>--><?php //echo $entry['groupname']; ?><!--</b><<grou>git git a</grou>
<!--        <div class="col_s_25">--><?php //echo get_string('quality', 'groupformation'); ?>
<!--            <b>--><?php //echo ($entry['groupquallity'] != 0) ? $entry['groupquallity'] : "-"; ?><!--</b>-->
<!--            <span class="toolt" tooltip="--><?php //echo get_string('quality_info', 'groupformation'); ?><!--"></span></div>-->
        <div class="col_m_25 bp_align_right-middle">
            <a href="<?php echo $entry['grouplink'][0]; ?>">
                <?php if (!$entry['grouplink'][1] == 'disabled') { ?>
                    <span class="gf_button gf_button_pill gf_button_tiny">
		    <?php echo get_string('to_groupview', 'groupformation'); ?>
		    </span>
                <?php } else { ?>
                    <button class="gf_button gf_button_pill gf_button_tiny" disabled>
                        <?php echo get_string('to_groupview', 'groupformation'); ?>
                    </button>
                <?php } ?>
            </a>
        </div>
        <div class="col_s_100 gf_group_links">

            <?php foreach ($entry['group_members'] as $user) { ?>
                <a href="<?php echo $user['link']; ?>"><?php echo $user['name']; ?></a>
            <?php } ?>

        </div>
    </div>
<?php } ?>