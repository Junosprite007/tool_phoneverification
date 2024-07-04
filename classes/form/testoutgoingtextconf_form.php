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

// require_once($CFG->libdir . '/formslib.php');
require_once(__DIR__ . '/../../lib.php');

/**
 * Form for testing outgoing texting configuration.
 *
 * @package     tool_phoneverification
 * @copyright   2024 onwards Joshua Kirby <josh@funlearningcompany.com>
 * @author      Joshua Kirby
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class testoutgoingtextconf_form extends \moodleform {
    /**
     * Add elements to form
     */
    public function definition() {
        $mform = $this->_form;
        // $returnurl = new \moodle_url('/admin/testoutgoingtextconf.php');
        // $verifyotp_form = new \tool_phoneverification\form\verifyotp_form(null, ['returnurl' => $returnurl]);

        // global $DB;

        // Recipient.
        global $USER;
        $userid = $USER->id;
        $editprofileurl = new \moodle_url('/user/edit.php', array('id' => $userid));
        $editprofilelink = \html_writer::link($editprofileurl, get_string('editmyprofile'));
        $providerconfigurl = new \moodle_url('/admin/settings.php?section=managetoolphoneverification');
        $providerconfiglink = \html_writer::link($providerconfigurl, get_string('phoneproviderconfiguration', 'tool_phoneverification'));

        // Phone number.
        $phone1 = tool_phoneverification_format_phone_number($USER->phone1);
        $phone2 = tool_phoneverification_format_phone_number($USER->phone2);
        if ($phone1) {
            $phone1formatted = preg_replace("/^\+(\d{1})(\d{3})(\d{3})(\d{4})$/", "+$1 ($2) $3-$4", $phone1);
        }

        if ($phone1) {
            $phone2formatted = preg_replace("/^\+(\d{1})(\d{3})(\d{3})(\d{4})$/", "+$1 ($2) $3-$4", $phone2);
        }
        if ($phone1formatted === $phone2formatted) {
            $phoneoptions = [$phone1 => $phone1formatted];
        } else {
            $phoneoptions = [$phone1 => $phone1formatted, $phone2 => $phone2formatted];
        }
        // $phoneoptions = [$phone1 => $phone1formatted, $phone2 => $phone2formatted];
        $phoneselected = '';

        // Set the selected phone number for setDefault later.
        if (($phoneoptions[$phone1] && $phoneoptions[$phone2]) || ($phoneoptions[$phone1])) {
            $phoneselected = $phoneoptions[$phone1];
        } elseif ($phoneoptions[$phone2]) {
            $phoneselected = $phoneoptions[$phone2];
        }

        // Provider dropdown.
        $providerstoshow = tool_phoneverification_providers_to_show(get_config('tool_phoneverification'));
        // echo '<br>';
        // echo '<br>';
        // echo '<br>';
        // var_dump("\$providerstoshow: ");
        // var_dump($providerstoshow);

        if (!$providerstoshow) {
            // No providers configured.
            $mform->addElement(
                'static',
                'noproviderfound',
                get_string('selectphonetoverify', 'tool_phoneverification'),
                new \lang_string('noproviderfound', 'tool_phoneverification', $providerconfiglink)
            );
            $mform->addRule('noproviderfound', get_string('required'), 'required');
        } else {
            $mform->addElement('select', 'provider', get_string('selectprovider', 'tool_phoneverification'), $providerstoshow);
            $mform->setType('provider', PARAM_TEXT);
            $mform->addRule('provider', get_string('required'), 'required');
        }

        if (!$phoneselected) {
            // No phone numbers available.
            $mform->addElement(
                'static',
                'nophonefound',
                get_string('selectphonetoverify', 'tool_phoneverification'),
                new \lang_string('nophonefound', 'tool_phoneverification', $editprofilelink)
            );
            $mform->addRule('nophonefound', get_string('required'), 'required');
        } else {
            // if ($phone1 === $phone2) {
            //     array_pop($phoneoptions);

            //     echo '<br>';
            //     echo '<br>';
            //     var_dump("\$phoneoptions: ");
            //     var_dump($phoneoptions);
            // }
            // if ($DB->get_record('tool_phoneverification', ['userid' => $USER->id, ])) {
            // $mform->addElement(
            //     'static',
            //     'verificationstatus',
            //     get_string('verificationstatus', 'tool_phoneverification'),
            //     new \lang_string('phonealreadyverified', 'tool_phoneverification')
            // );
            // }
            $mform->addElement('select', 'tonumber', get_string('selectphonetoverify', 'tool_phoneverification'), $phoneoptions);
            $mform->setType('tonumber', PARAM_TEXT);
            $mform->setDefault('tonumber', $phoneselected);
            $mform->addRule('tonumber', get_string('required'), 'required');
        }

        // echo '<br>';
        // var_dump("\$phoneselected: ");
        // var_dump($phoneselected);
        // echo '<br>';
        // echo '<br>';
        // var_dump("\$phoneoptions: ");
        // var_dump($phoneoptions);


        // // Additional subject text.
        // $textoptions = ['maxlength' => '6'];
        // $mform->addElement('text', 'otp', get_string('subjectadditional', 'tool_phoneverification'), $textoptions);
        // $mform->setType('otp', PARAM_TEXT);

        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'send', get_string('sendtest', 'tool_phoneverification'));
        $buttonarray[] = $mform->createElement('cancel');

        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
        // echo '<br>';
        // echo '<br>';
        // var_dump("\$buttonarray: ");
        // var_dump($buttonarray);
        // echo '<br>';
        // echo '<br>';
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
