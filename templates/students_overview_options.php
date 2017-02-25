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
<div class="gf_pad_header_small"> <?php echo get_string('options', 'groupformation'); ?> </div>
<div class="gf_pad_content" style="">
    <div class="grid">
        <div class="col_m_100">
            <input type="hidden" name="id" value="<?php echo $this->_['cmid']; ?>"/>
            <?php if (array_key_exists('consentvalue', $this->_)): ?>
            <div style="padding-bottom: 10px;">
                <div>
                    <p>
                        <b>
                            <?php echo get_string('consent_opt_in', 'groupformation'); ?>
                        </b>
                    </p>
                </div>
                <div>
                    <p>
                        <?php echo get_string('consent_header', 'groupformation'); ?>
                    </p>
                    <p>
                        <?php echo get_string('consent_message', 'groupformation'); ?>
                    </p>
                </div>
                <div>
                    <p style="margin-left: 10px;">
                        <input type="checkbox" name="consent"
                            <?php echo ($this->_['consentvalue']) ? 'checked disabled' : '' ?>
                               value="<?php echo $this->_['consentvalue']; ?>"/>
                        <?php echo ' ' . get_string('consent_agree', 'groupformation'); ?>
                    </p>
                </div>
            </div>
            <?php endif; ?>
            <?php if (array_key_exists('participant_code', $this->_) && $this->_['participant_code']): ?>
                <div style="padding-bottom: 10px;">
                    <div>
                        <p>
                            <b>
                                <?php echo get_string('participant_code_title', 'groupformation'); ?>
                            </b>
                        </p>
                    </div>

                    <div><?php if ($this->_['participant_code_user'] === ''): ?>
                            <p>
                                <?php echo get_string('participant_code_header', 'groupformation'); ?>
                            </p>
                            <p>
                                <?php echo get_string('participant_code_rules', 'groupformation'); ?>
                            </p>
                            <p>
                                <?php echo get_string('participant_code_example', 'groupformation'); ?>
                            </p>
                        <?php endif; ?>
                        <p>
                            <?php echo get_string('participant_code_footer', 'groupformation'); ?>
                        </p>
                    </div>

                    <div>
                        <p style="margin-left: 10px;">
                            <input type="text"
                                <?php echo ($this->_['participant_code_user'] != '') ? 'checked disabled' : '' ?>
                                   name="participantcode"
                                   value="<?php echo $this->_['participant_code_user']; ?>"/>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
            <p><?php echo $this->_['buttons_infos']; ?></p>
            <?php foreach ($this->_['buttons'] as $button) { ?>
                <button type="<?php echo $button['type']; ?>"
                        name="<?php echo $button['name']; ?>"
                        value="<?php echo $button['value']; ?>"
                        class="gf_button gf_button_pill gf_button_small"
                        <?php echo $button['state']; ?>
                >
                    <?php echo $button['text']; ?>
                </button>
            <?php } ?>
            <!--            </form>-->
        </div>
    </div>
</div>