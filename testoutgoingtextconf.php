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

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

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
    $textuser = new stdClass();
    $textuser->text = $data->recipient;
    $textuser->id = -99;

    // Get the user who will send this text (From:).
    $textuserfrom = $USER;
    if ($data->from) {
        if (!$userfrom = \core_user::get_user_by_text($data->from)) {
            $userfrom = \core_user::get_user_by_username($data->from);
        }
        if (!$userfrom && validate_text($data->from)) {
            $dummyuser = \core_user::get_user(\core_user::NOREPLY_USER);
            $dummyuser->id = -1;
            $dummyuser->text = $data->from;
            $dummyuser->firstname = $data->from;
            $textuserfrom = $dummyuser;
        } else if ($userfrom) {
            $textuserfrom = $userfrom;
        }
    }

    // Get the date the text will be sent.
    $timestamp = userdate(time(), get_string('strftimedatetimeaccurate', 'core_langconfig'));

    // Build the text subject.
    $subjectparams = new stdClass();
    $subjectparams->site = format_string($SITE->fullname, true, ['context' => context_system::instance()]);
    if (isset($data->additionalsubject)) {
        $subjectparams->additional = format_string($data->additionalsubject);
    }
    $subjectparams->time = $timestamp;

    $subject = get_string('testoutgoingtextconf_subject', 'tool_phoneverification', $subjectparams);
    $messagetext = get_string('testoutgoingtextconf_message', 'tool_phoneverification', $timestamp);

    // Manage Moodle debugging options.
    $debuglevel = $CFG->debug;
    $debugdisplay = $CFG->debugdisplay;
    $debugsmtp = $CFG->debugsmtp ?? null; // This might not be set as it's optional.
    $CFG->debugdisplay = true;
    $CFG->debugsmtp = true;
    $CFG->debug = 15;

    // Send test text.
    ob_start();
    $success = text_to_user($textuser, $textuserfrom, $subject, $messagetext);
    $smtplog = ob_get_contents();
    ob_end_clean();

    // Restore Moodle debugging options.
    $CFG->debug = $debuglevel;
    $CFG->debugdisplay = $debugdisplay;

    // Restore the debugsmtp config, if it was set originally.
    unset($CFG->debugsmtp);
    if (!is_null($debugsmtp)) {
        $CFG->debugsmtp = $debugsmtp;
    }

    if ($success) {
        $msgparams = new stdClass();
        $msgparams->fromtext = $textuserfrom->text;
        $msgparams->totext = $textuser->text;
        $msg = get_string('testoutgoingtextconf_sentmail', 'tool_phoneverification', $msgparams);
        $notificationtype = 'notifysuccess';
    } else {
        $notificationtype = 'notifyproblem';
        // No communication between Moodle and the SMTP server - no error output.
        if (trim($smtplog) == false) {
            $msg = get_string('testoutgoingtextconf_errorcommunications', 'tool_phoneverification');
        } else {
            $msg = $smtplog;
        }
    }

    // Show result.
    echo $OUTPUT->notification($msg, $notificationtype);
}

$form->display();
echo $OUTPUT->footer();
