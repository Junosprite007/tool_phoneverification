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
 * Verify OTP code.
 *
 * @package     tool_phoneverification
 * @copyright   2024 onwards Joshua Kirby <josh@funlearningcompany.com>
 * @author      Joshua Kirby
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

global $SITE, $USER;

// This is an admin page.
admin_externalpage_setup('verifyotp');

$headingtitle = get_string('verifyotp', 'tool_phoneverification');
$homeurl = new moodle_url('/admin/category.php', array('category' => 'phone'));
$returnurl = new moodle_url('/admin/verifyotp.php');
$form = new tool_phoneverification\form\verifyotp_form(null, ['returnurl' => $returnurl]);

if ($form->is_cancelled()) {
    redirect($homeurl);
}

// Display the page.
echo $OUTPUT->header();
echo $OUTPUT->heading($headingtitle);

// Displaying notextever warning.
if (!empty($CFG->notextever)) {
    $msg = get_string('notexteverwarning', 'tool_phoneverification');
    echo $OUTPUT->notification($msg, \core\output\notification::NOTIFY_ERROR);
}

$data = $form->get_data();
if ($data) {
    $textuser = new stdClass();
    $textuser->tonumber = $data->tonumber;
    $textuser->notes = [
        'shortname' => $SITE->shortname,
        'provider' => $data->provider
    ];

    // $textuser->id = -99;
    $responseobject = tool_phoneverification_send_secure_otp($textuser->notes['provider'], $textuser->tonumber);

    // We're eventually going to need to handle Moodle debugging options. Check out 'testoutgoingmailconf.php' for an example.

    if ($responseobject->success) {
        $msgparams = new stdClass();
        $msgparams->tonumber = $textuser->tonumber;
        $msg = get_string('senttextsuccess', 'tool_phoneverification', $msgparams);
        $notificationtype = 'notifysuccess';
    } else {
        $notificationtype = 'notifyproblem';
        $msg = get_string('senttextfailure', 'tool_phoneverification', $responseobject->errormessage);
    }

    // // Show result.
    echo $OUTPUT->notification($msg, $notificationtype);

    // if ($verifydata) {
    //     var_dump($verifydata);
    // }
}

$form->display();
echo $OUTPUT->footer();
