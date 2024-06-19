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
 * Test output mail configuration page
 *
 * @copyright 2019 Victor Deniz <victor@moodle.com>, based on Michael Milette <michael.milette@tngconsulting.ca> code
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_reportbuilder\external\columns\sort\get;

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

global $SITE;

// This is an admin page.
admin_externalpage_setup('testoutgoingtextconf');

$headingtitle = get_string('testoutgoingtextconf', 'tool_phoneverification');
$homeurl = new moodle_url('/admin/category.php', array('category' => 'phone'));
$returnurl = new moodle_url('/admin/testoutgoingtextconf.php');

// This form is located at admin/tool/phoneverification/classes/form/testoutgoingtextconf_form.php.
$form = new tool_phoneverification\form\testoutgoingtextconf_form(null, ['returnurl' => $returnurl]);
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
    // echo "<br />";
    // echo "<br />";
    // echo '$data: ';
    // var_dump($data);
    // echo "<br />";
    // echo "<br />";
    $textuser = new stdClass();
    $textuser->tonumber = $data->tonumber;

    // $notes = new stdClass();
    // $notes->shortname = $SITE->shortname;
    // $notes->provider = $data->provider;

    // $textuser->notes = $notes;

    $textuser->notes = [
        'shortname' => $SITE->shortname,
        'provider' => $data->provider
    ];

    // No longer using the message field, since we're dealing with OTPs now.
    // $textuser->message = $data->message ? $data->message : get_string('testoutgoingtextconf_message', 'tool_phoneverification', $textuser->notes);

    $textuser->id = -99;

    // Add the cases for each provider.
    // $responseobject = tool_phoneverification_send_sms($textuser->notes['provider'], $textuser->tonumber, $textuser->message);
    $responseobject = tool_phoneverification_send_secure_otp($textuser->notes['provider'], $textuser->tonumber);

    // // Get the user who will send this text (From:).
    // $textuserfrom = $USER;
    // if ($data->from) {
    //     if (!$userfrom = \core_user::get_user_by_text($data->from)) {
    //         $userfrom = \core_user::get_user_by_username($data->from);
    //     }
    //     if (!$userfrom && tool_phoneverification_validate_phone_number($data->from)) {
    //         $dummyuser = \core_user::get_user(\core_user::NOREPLY_USER);
    //         $dummyuser->id = -1;
    //         $dummyuser->text = $data->from;
    //         $dummyuser->firstname = $data->from;
    //         $textuserfrom = $dummyuser;
    //     } else if ($userfrom) {
    //         $textuserfrom = $userfrom;
    //     }
    // }

    // // Get the date the text will be sent.
    // $timestamp = userdate(time(), get_string('strftimedatetimeaccurate', 'core_langconfig'));

    // // Build the text subject.
    // $subjectparams = new stdClass();
    // $subjectparams->site = format_string($SITE->fullname, true, ['context' => context_system::instance()]);
    // if (isset($data->additionalsubject)) {
    //     $subjectparams->additional = format_string($data->additionalsubject);
    // }
    // $subjectparams->time = $timestamp;

    // $subject = get_string('testoutgoingtextconf_subject', 'tool_phoneverification', $subjectparams);
    // $messagetext = get_string('testoutgoingtextconf_message', 'tool_phoneverification', $timestamp);


    // We're eventually going to need to hand Moodle debugging options. Check out 'testoutgoingmailconf.php' for an example.

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
}

$form->display();
echo $OUTPUT->footer();
