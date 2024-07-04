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
 * Verifying text OTP form
 *
 * @package     tool_phoneverification
 * @copyright   2024 onwards Joshua Kirby <josh@funlearningcompany.com>
 * @author      Joshua Kirby
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_phoneverification\form;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class verifyotp_form extends \moodleform {
    //Add elements to form
    public function definition() {
        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('text', 'otp', get_string('otp', 'tool_phoneverification')); // Add elements
        $mform->setType('otp', PARAM_NOTAGS); //Set type of element
        $mform->addRule('otp', null, 'required', null, 'client');

        $this->add_action_buttons(false, get_string('verifyotp', 'tool_phoneverification'));
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
        // global $USER;
        // $phoneoptions = ['phone1' => $USER->phone1, 'phone2' => $USER->phone2];
        $errors = parent::validation($data, $files);
        // echo '<br>';
        // echo '<br>';
        // $data['tonumber'] = $phoneoptions[$data['tonumber']];
        // var_dump("Form validation \$data: ");
        // var_dump($data['tonumber']);

        // $phoneoptions = ['phone1' => $USER->phone1, 'phone2' => $USER->phone2];

        if (isset($data['tonumber']) && $data['tonumber']) {
            // $userrecipient = \core_user::get_user_by_username($data['tonumber']);

            // if (!$userrecipient && !tool_phoneverification_validate_phone_number($data['tonumber'])) {
            //     $errors['tonumber'] = get_string('recipientphone_invalid', 'tool_phoneverification');
            // }
        }

        return $errors;
    }
}
