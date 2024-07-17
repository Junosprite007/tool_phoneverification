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

require_once(__DIR__ . '/../../lib.php');

class verifyotp_form extends \moodleform {
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
        $errors = parent::validation($data, $files);

        if (isset($data['otp'])) {
            $otp = $data['otp'];

            // // Check if the OTP entered is formatted correctly.
            $messages = array();
            if (!is_number($otp)) {
                array_push($messages, get_string('enternumbersonly', 'tool_phoneverification'));
            }
            if (strlen($otp) != 6) {
                array_push($messages, get_string('enterexactly6digits', 'tool_phoneverification'));
            }
            if (count($messages) > 0) {
                $errors['otp'] = join("<br>", $messages);
            }
        }
        return $errors;
    }
}
