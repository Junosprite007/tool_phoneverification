<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     tool_phoneverification
 * @category    string
 * @copyright   2024 Joshua Kirby <josh@funlearningcompany.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['awssns'] = 'AWS SNS';
$string['awssns_desc'] = 'Enter AWS SNS configuration here. An account for AWS SNS can be created {$a}.';
$string['awssnsaccesskey'] = 'AWS SNS Access Key';
$string['awssnsaccesskey_desc'] = 'Enter your AWS SNS Access Key here.';
$string['awssnsregion'] = 'AWS SNS Region';
$string['awssnsregion_desc'] = 'Enter your AWS SNS Region here.';
$string['awssnssecretkey'] = 'AWS SNS Secret Key';
$string['awssnssecretkey_desc'] = 'Enter your AWS SNS Secret Key here.';
$string['here'] = 'here';
$string['infobip'] = 'Infobip';
$string['infobip_desc'] = 'Enter Infobip configuration here. An account for Infobip can be accessed/created {$a}.';
$string['infobipapibaseurl'] = 'Infobip API base URL';
$string['infobipapibaseurl_desc'] = 'Enter the API base URL for Infobip.';
$string['infobipapikey'] = 'Infobip API key';
$string['infobipapikey_desc'] = 'Enter the API key for Infobip.';
$string['nophonefound'] = 'No phone found! Go to {$a} > Optional to add your phone number.';
$string['phone'] = 'Phone';
$string['phone1'] = 'Phone';
$string['phone2'] = 'Phone';
$string['phoneproviderconfiguration'] = 'Phone provider configuration';
$string['phonesettings'] = 'Phone settings';
$string['pluginname'] = 'Phone verification';
$string['profilesettings'] = 'profile settings';
$string['provider'] = 'Provider';
$string['provider_desc'] = 'Select the provider to use for sending SMS messages.';
$string['showinnavigation'] = 'Show in navigation';
$string['showinnavigation_desc'] = 'This setting determines whether the phone verification plugin will be shown in the navigation.';
$string['testoutgoingtextconf'] = 'Test outgoing text configuration';
$string['testoutgoingtextdetail'] = 'Note: Before testing, please save your configuration.<br />{$a}';
$string['errorcommunications'] = 'Your site couldn\'t communicate with your mail server. Please check your outgoing mail configuration.';
$string['message'] = 'This is a test message to confirm that you have successfully configured your site\'s outgoing mail.  Sent: {$a}';
$string['fromtext'] = 'From username or email address';
$string['fromtext_help'] = 'This field emulates sending the message from that user, but the From header used in the real email sent will depend on other settings such as allowedemaildomains';
$string['fromtext_invalid'] = 'Invalid From username or email. Must be a valid email format or an existing username in Moodle.';
$string['sendtest'] = 'Send a test message';
$string['sentmail'] = 'This site has successfully sent a test message to the mail server.<br />From: {$a->fromemail}<br />To: {$a->toemail}';
$string['subject'] = '{$a->site}: test message. {$a->additional} Sent: {$a->time}';
$string['subjectadditional'] = 'Additional subject';
$string['selectphonetoverify'] = 'Select a phone number to verify';
$string['twilio'] = 'Twilio';
$string['twilio_desc'] = 'Enter Twilio configuration here. An account for Twilio can be accessed/created {$a}.';
$string['twilioaccountsid'] = 'Twilio account SID';
$string['twilioaccountsid_desc'] = 'Enter the account SID for Twilio.';
$string['twilioauthtoken'] = 'Twilio auth token';
$string['twilioauthtoken_desc'] = 'Enter the auth token for Twilio.';
$string['twilionumber'] = 'Twilio number';
$string['twilionumber_desc'] = 'Enter the Twilio number to send messages from.';
