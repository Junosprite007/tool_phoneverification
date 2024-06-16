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
        global $USER;
        $userid = $USER->id;
        $editprofileurl = new \moodle_url('/user/edit.php', array('id' => $userid));
        // $editprofileurl = new moodle_url('/admin/tool/phoneverification/testoutgoingtextconf.php');
        $link = \html_writer::link($editprofileurl, get_string('editmyprofile'));
        $phone1 = $USER->phone1; // Get 'Phone' number from user profile
        $phone2 = $USER->phone2; // Get 'Mobile phone' number from user profile
        $nophone = false;
        $options = [];

        if ($phone1 && $phone2) {
            $options = [
                'phone1' => $phone1,
                'phone2' => $phone2
            ];
        } elseif ($phone1) {
            $options = [
                'phone1' => $phone1
            ];
            $mform->setDefault('phonenumber', 'phone1');
        } elseif ($phone2) {
            $options = [
                'phone2' => $phone2
            ];
            $mform->setDefault('phonenumber', 'phone2');
        } else {
            $nophone = true;
        }

        if ($nophone) {
            // No phone numbers available.
            $mform->setDefault('phonenumber', '');
            $mform->addElement(
                'static',
                'nophonefound',
                get_string('selectphonetoverify', 'tool_phoneverification'),
                new \lang_string('nophonefound', 'tool_phoneverification', $link)
            );
            $mform->addRule('nophonefound', get_string('required'), 'required');
        } else {
            $mform->addElement('select', 'phonenumber', get_string('selectphonetoverify', 'tool_phoneverification'), $options);
            $mform->setType('phonenumber', PARAM_TEXT);
            $mform->addRule('phonenumber', get_string('required'), 'required');
        }

        // Additional subject text.
        $options = ['size' => '25'];
        $mform->addElement('textarea', 'additionalsubject', get_string('subjectadditional', 'tool_phoneverification'), $options);
        $mform->setType('additionalsubject', PARAM_TEXT);

        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'send', get_string('sendtest', 'tool_phoneverification'));
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

        if (isset($data['phonenumber']) && $data['phonenumber']) {
            $userrecipient = \core_user::get_user_by_username($data['phonenumber']);

            if (!$userrecipient && !\tool_phoneverification_validate_phone_number($data['phonenumber'])) {
                $errors['phonenumber'] = get_string('recipientphone_invalid', 'tool_phoneverification');
            }
        }

        return $errors;
    }
}
