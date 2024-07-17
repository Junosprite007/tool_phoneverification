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

$string['allphonesalreadyverified'] = 'All of your phone numbers have already been verified!';
$string['awssns'] = 'AWS SNS';
$string['awssns_desc'] = 'Enter AWS SNS configuration here. An account for AWS SNS can be created {$a}.';
$string['awssnsaccesskey'] = 'AWS SNS Access Key';
$string['awssnsaccesskey_desc'] = 'Enter your AWS SNS Access Key here.';
$string['awssnsregion'] = 'AWS SNS Region';
$string['awssnsregion_desc'] = 'Enter your AWS SNS Region here.';
$string['awssnssecretkey'] = 'AWS SNS Secret Key';
$string['awssnssecretkey_desc'] = 'Enter your AWS SNS Secret Key here.';
$string['caughtexception'] = 'Caught exception: {$a}';
$string['codeconfirmed'] = 'Code confirmed! Your phone number {$a->tophonenumber} is now verified.';
$string['dbrecordidnotset'] = 'The database record ID was not set properly.';
$string['enterexactly6digits'] = '- Must be exactly 6 digits.';
$string['enternumbersonly'] = '- Enter numbers only.';
$string['errorcommunications'] = 'Your site couldn\'t communicate with your mail server. Please check your outgoing mail configuration.';
$string['fromtext'] = 'From username or email address';
$string['fromtext_help'] = 'This field emulates sending the message from that user, but the From header used in the real email sent will depend on other settings such as allowedemaildomains';
$string['fromtext_invalid'] = 'Invalid From username or email. Must be a valid email format or an existing username in Moodle.';
$string['here'] = 'here';
$string['httprequestfailed'] = 'HTTP request failed.';
$string['httprequestfailedwithcode'] = 'HTTP request failed with code {$a->httpcode}<br />cURL code: {$a->curlcode}';
$string['infobip'] = 'Infobip';
$string['infobip_desc'] = 'Enter Infobip configuration here. An account for Infobip can be accessed/created {$a}.';
$string['infobipapibaseurl'] = 'Infobip API base URL';
$string['infobipapibaseurl_desc'] = 'Enter the API base URL for Infobip.';
$string['infobipapikey'] = 'Infobip API key';
$string['infobipapikey_desc'] = 'Enter the API key for Infobip.';
$string['invalidphonenumberformat'] = 'Invalid phone number format.';
$string['message'] = 'This is a test message to confirm that you have successfully configured your site\'s outgoing mail.  Sent: {$a}';
$string['nophonefound'] = 'No phone found! Go to {$a} > Optional to add your phone number.';
$string['nophonestoverify'] = 'It looks like you don\'t have any phones that need verification.';
$string['noproviderfound'] = 'Provider not configured! Configure a provider here: {$a}';
$string['notasupportedcountry'] = '{$a} is not a supported country.';
$string['novalidotpsfound'] = 'No valid OTPs found for you. You\'ll need to send a verification code first. You can do that here: {$a}';
$string['otp'] = 'OTP';
$string['otpdoesnotmatch'] = 'The code you entered does not match the one we sent you. Please try again.';
$string['otperror'] = 'OTP error:<br /><br />{$a}';
$string['otphasexpired'] = 'It looks like this code has expired. You can request another one here: {$a}';
$string['otpsdonotmatch'] = 'The code you entered does not match any of the verification codes we have for you. Please try again.';
$string['otpforthisnumberalreadyexists'] = 'A code for this number already exists. Please check your text messages. It may take a few minutes to receive the code.';
$string['phone'] = 'Phone';
$string['phonealreadyverified'] = 'It looks like this phone number has already been verified.';
$string['phonefieldsdonotexist'] = 'Phone fields "phone1" or "phone2" do not exist.';
$string['phoneproviderconfiguration'] = 'Phone provider configuration';
$string['phonesettings'] = 'Phone settings';
$string['phoneverificationcodeforflip'] = '{$a} is your phone verification code for FLIP.';
$string['pluginname'] = 'Phone verification';
$string['profilesettings'] = 'profile settings';
$string['provider'] = 'Provider';
$string['provider_desc'] = 'Select the provider to use for sending SMS messages.';
$string['recipientphone_invalid'] = 'Invalid recipient phone number. Must be a valid phone number.';
$string['showinnavigation'] = 'Show in navigation';
$string['showinnavigation_desc'] = 'This setting determines whether the phone verification plugin will be shown in the navigation.';
$string['selectphonetoverify'] = 'Select a phone number to verify';
$string['selectprovider'] = 'Select provider to use';
$string['sendtest'] = 'Send a test message';
$string['senttextsuccess'] = 'Text message for verification was successfully sent to {$a->tonumber}<br />Now use the code to verify your phone number.';
$string['senttextfailure'] = 'Message did not send:<br /><br />{$a}';
$string['somethingwentwrong'] = 'Something went wrong... This probably needs to be looked at by a programmer.';
$string['subject'] = '{$a->site}: test message. {$a->additional} Sent: {$a->time}';
$string['subjectadditional'] = 'Additional subject';
$string['testoutgoingtextconf'] = 'Test outgoing text configuration';
$string['testoutgoingtextdetail'] = 'Note: Before testing, please save your configuration.<br />{$a}';
$string['testoutgoingtextconf_message'] = 'Here\'s your text from {$a->shortname} via {$a->provider}!';
$string['twilio'] = 'Twilio';
$string['twilio_desc'] = 'Enter Twilio configuration here. An account for Twilio can be accessed/created {$a}.';
$string['twilioaccountsid'] = 'Twilio account SID';
$string['twilioaccountsid_desc'] = 'Enter the account SID for Twilio.';
$string['twilioauthtoken'] = 'Twilio auth token';
$string['twilioauthtoken_desc'] = 'Enter the auth token for Twilio.';
$string['twilionumber'] = 'Twilio number';
$string['twilionumber_desc'] = 'Enter the Twilio number to send messages from.';
$string['verificationcode'] = 'Verification code';
$string['verificationstatus'] = 'Verification status';
$string['verifyotp'] = 'Verify OTP';
$string['verifyotpdetail'] = 'Verify your phone number with an existing code.<br />{$a}';
$string['wait10minutes'] = 'You\'ll have to wait 10 minutes before you can request another code.';
$string['wecurrentlyonlyacceptusphonenumbers'] = 'We currently only accept U.S. phone numbers. You can use most standard ways of typing a phone number like 2345678910 or +1 (234) 567-8910.';
