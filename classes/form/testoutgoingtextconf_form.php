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
 * Testing outgoing text configuration form
 *
 * @package     tool_phoneverification
 * @copyright   2024 onwards Joshua Kirby <josh@funlearningcompany.com>
 * @author      Joshua Kirby
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_phoneverification\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Test mail form
 *
 * @package    core
 * @copyright 2019 Victor Deniz <victor@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class testoutgoingtextconf_form extends \moodleform {
    /**
     * Add elements to form
     */
    public function definition() {
        $mform = $this->_form;

        // Recipient.
        $options = ['maxlength' => '100', 'size' => '25', 'autocomplete' => 'text'];
        $mform->addElement('textarea', 'recipient', get_string('testoutgoingtextconf_totext', 'tool_phoneverification'), $options);
        $mform->setType('recipient', PARAM_TEXT);
        $mform->addRule('recipient', get_string('required'), 'required');

        // From user.
        $options = ['maxlength' => '100', 'size' => '25'];
        $mform->addElement('text', 'from', get_string('testoutgoingtextconf_fromtext', 'tool_phoneverification'), $options);
        $mform->setType('from', PARAM_TEXT);
        $mform->addHelpButton('from', 'testoutgoingtextconf_fromtext', 'tool_phoneverification');

        // Additional subject text.
        $options = ['size' => '25'];
        $mform->addElement('textarea', 'additionalsubject', get_string('testoutgoingtextconf_subjectadditional', 'tool_phoneverification'), $options);
        $mform->setType('additionalsubject', PARAM_TEXT);

        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'send', get_string('testoutgoingtextconf_sendtest', 'tool_phoneverification'));
        $buttonarray[] = $mform->createElement('cancel');

        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
    }

    /**
     * Validate Form field, should be a valid text format or a username that matches with a Moodle user.
     *
     * @param array $data
     * @param array $files
     * @return array
     * @throws \dml_exception|\coding_exception
     */
    public function validation($data, $files): array {
        $errors = parent::validation($data, $files);

        if (isset($data['from']) && $data['from']) {
            $userfrom = \core_user::get_user_by_username($data['from']);

            if (!$userfrom && !validate_text($data['from'])) {
                $errors['from'] = get_string('testoutgoingtextconf_fromtext_invalid', 'tool_phoneverification');
            }
        }

        return $errors;
    }
}
