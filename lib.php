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
 * Library of functions and constants for the Phone verification tool.
 *
 * @package     tool_phoneverification
 * @copyright   2024 onwards Joshua Kirby <josh@funlearningcompany.com>
 * @author      Joshua Kirby
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . '/moodlelib.php');

/**
 * Validates a mobile phone number to make sure it makes sense.
 *
 * @param string $phonenumber The mobile phone number to validate.
 * @param string $country The country code to use.
 * @return string
 */
function tool_phoneverification_parse_phone_number($phonenumber, $country = 'US') {
    // Remove commonly used characters from the phone number that are not numbers: ().-+ and the white space char.
    $parsedphonenumber = preg_replace("/[\(\)\-\s+\.]/", "", $phonenumber);

    try {
        if (!ctype_digit($parsedphonenumber)) {
            throw new \Exception(get_string('invalidphonenumberformat', 'tool_phoneverification') . get_string('wecurrentlyonlyacceptusnumbers', 'tool_phoneverification'));
        }
    } catch (\Exception $e) {
        return $e->getMessage();
    }

    switch ($country) {
        case 'US':
            // Check if the number is not empty, if it only contains digits, and if it is a valid 10 or 11 digit United States phone number.
            try {
                if ((strlen($parsedphonenumber) == 10) && $phonenumber[0] != 1) {
                    $parsedphonenumber = "+1" . $parsedphonenumber;
                } elseif ((strlen($parsedphonenumber) == 11) && $phonenumber[0] == 1) {
                    $parsedphonenumber = "+" . $parsedphonenumber;
                } else {
                    throw new \Exception(new lang_string('invalidphonenumber', 'tool_phoneverification') . new lang_string('wecurrentlyonlyacceptusnumbers', 'tool_phoneverification'));
                }
            } catch (\Exception $e) {
                return $e->getMessage();
            }
            break;
        default:
            return new lang_string('notasupportedcountry', 'tool_phoneverification', $country);
    }
    return $parsedphonenumber;
}

/**
 * Validates a cell phone number to make sure it makes sense.
 *
 * @param string $prunedphonenumber The already parsed phone number. This must follow the exact form as follows: +12345678910
 * @return string
 */
function tool_phoneverification_format_phone_number($prunedphonenumber) {
    return preg_replace("/^\+(\d{1})(\d{3})(\d{3})(\d{4})$/", "+$1 ($2) $3-$4", $prunedphonenumber);
}

/**
 * Validates a cell phone number to make sure it makes sense.
 *
 * @param object $allphoneconfigs An object containing all the phone configuration settings for every provider.
 * @return array
 */
function tool_phoneverification_providers_to_show($allphoneconfigs) {
    $providers = [];

    if ($allphoneconfigs->infobipapikey && $allphoneconfigs->infobipapibaseurl) {
        $providers['infobip'] = get_string('infobip', 'tool_phoneverification');
    }

    return $providers;
}

/**
 * Sends an SMS message to a phone number.
 *
 * @param string $provider The provider to use for sending the SMS message.
 * @param string $tonumber The phone number to send the SMS message to.
 * @param string $message The message to send in the SMS message.
 * @return object
 */
function tool_phoneverification_send_sms($provider, $tonumber, $message) {
    global $SITE;

    $responseobject = new stdClass();
    try {
        switch ($provider) {
            case 'infobip':
                // Just for testing:
                // $responseobject->success = true;
                // break;

                $infobipapikey = get_config('tool_phoneverification', 'infobipapikey');
                $infobipapibaseurl = get_config('tool_phoneverification', 'infobipapibaseurl');
                $curl = new curl();

                // Set headers
                $headers = [
                    'Authorization: App ' . $infobipapikey,
                    'Content-Type: application/json',
                    'Accept: application/json'
                ];

                $curl->setHeader($headers);
                $postdata = '{"messages":[{"destinations":[{"to":"' . $tonumber . '"}],"from":"' . $SITE->shortname . '","text":"' . $message . '"}]}';

                // Make the request
                $responseobject->response = $curl->post('https://' . $infobipapibaseurl . '/sms/2/text/advanced', $postdata);

                // Get the HTTP response code
                $info = $curl->get_info();
                $responseobject->errormessage = '';
                $responseobject->errorobject = new stdClass();

                if ($info['http_code'] >= 200 && $info['http_code'] < 300) {
                    // The request was successful
                    $responseobject->success = true;
                } else {
                    // The request failed
                    $responseobject->errorobject->httpcode = $info['http_code'];
                    $responseobject->errorobject->curlcode = $curl->get_errno();
                    $responseobject->errormessage = get_string('httprequestfailedwithcode', 'tool_phoneverification', $responseobject->errorobject);
                    $responseobject->success = false;
                    throw new moodle_exception('httprequestfailed', 'tool_phoneverification', '', null, $responseobject->errormessage);
                }
                break;
            case 'twilio':
                // $twilioaccountsid = get_config('tool_phoneverification', 'twilioaccountsid');
                // $twilioauthtoken = get_config('tool_phoneverification', 'twilioauthtoken');
                // $twilionumber = get_config('tool_phoneverification', 'twilionumber');

                break;
            case 'awssns':
                // $awssnsaccesskey = get_config('tool_phoneverification', 'awssnsaccesskey');
                // $awssnssecretkey = get_config('tool_phoneverification', 'awssnssecretkey');
                // $awssnsregion = get_config('tool_phoneverification', 'awssnsregion');

                break;
            default:
                break;
        }
    } catch (Exception $e) {
        // Handle the exception
        $responseobject->errormessage = $e->getMessage();
    }
    return $responseobject;
}

/**
 * Sends an SMS message to a phone number via POST and HTTPS.
 *
 * @param string $provider The provider to use for sending the SMS message.
 * @param string $tophonenumber The phone number to send the SMS message to.
 * @param int $ttl Time to live (TTL) in seconds until the OTP expires.
 * @return object
 */
function tool_phoneverification_send_secure_otp($provider, $tophonenumber, $ttl = 600) {
    global $USER, $DB, $SESSION;

    $responseobject = new stdClass();
    $verifyurl = new moodle_url('/admin/tool/phoneverification/verifyotp.php');

    try {

        $record = new stdClass();
        // Initialize $SESSION->otps.
        if (!isset($SESSION->otps)) {
            $SESSION->otps = new stdClass();
        }

        $otp = mt_rand(100000, 999999);

        // Test OTP
        $testotp = 345844;
        $message = get_string('phoneverificationcodeforflip', 'tool_phoneverification', $otp);
        $phone1 = tool_phoneverification_parse_phone_number($USER->phone1);
        $phone2 = tool_phoneverification_parse_phone_number($USER->phone2);
        if ($tophonenumber == $phone1) {
            $record->tophonename = 'phone1';
        } elseif ($tophonenumber == $phone2) {
            $record->tophonename = 'phone2';
        } else {
            throw new moodle_exception('phonefieldsdonotexist', 'tool_phoneverification');
        }

        $sessionotpcount = 0;
        $dbotpcount = 0;
        $sessionasarray = get_object_vars($SESSION->otps);
        $sqlconditions = ['userid' => $USER->id];
        $otprecords = $DB->get_records('tool_phoneverification_otp', $sqlconditions);
        $recordexists = false;

        if (!empty($sessionasarray)) {
            // Prune bad $SESSION->otps records.
            foreach ($SESSION->otps as $key => $entry) {
                $sessionotpcount++;
                $expired = $entry->expires <= time();
                $verified = $entry->phoneisverified;
                if ($expired && !$verified) {
                    // Removing old session info.
                    unset($SESSION->otps->$key);
                    $sessionotpcount--;
                }
            }
        }

        // Prune bad $DB records.
        if (!empty($otprecords)) {
            foreach ($otprecords as $key => $entry) {
                $dbotpcount++;
                $expired = $entry->expires <= time();
                $verified = $entry->phoneisverified;
                if ($expired && !$verified) {
                    $DB->delete_records('tool_phoneverification_otp', ['id' => $entry->id]);
                    $dbotpcount--;
                } else {
                    // Override the session to match what's in the DB.
                    $SESSION->otps->{$entry->tophonename} = $entry;
                }
            }
        }

        $recordexists = isset($SESSION->otps->{$record->tophonename});
        if ($recordexists) {
            $isverified = $SESSION->otps->{$record->tophonename}->phoneisverified;
        }

        // At this point, we are guaranteed that there are as many records in the DB
        // as there are in $SESSION->otps, and they hold the same info, though formatted differently.
        if (!$recordexists && $dbotpcount < 2) {

            // Create new record.
            $record->userid = $USER->id;
            $record->otp = password_hash($otp, PASSWORD_DEFAULT);  // Hash the OTP.
            $record->tophonenumber = $tophonenumber;
            $record->phoneisverified = 0;
            $record->timecreated = time();
            $record->timeverified = null;
            $record->expires = $record->timecreated + $ttl;  // OTP expires after 10 minutes.

            $SESSION->otps->{$record->tophonename} = $record;
            $SESSION->otps->{$record->tophonename}->id = $DB->insert_record('tool_phoneverification_otp', $record);
            $message = get_string('phoneverificationcodeforflip', 'tool_phoneverification', $otp);
            $responseobject = tool_phoneverification_send_sms($provider, $tophonenumber, $message);
        } elseif ($recordexists && $isverified) {
            throw new moodle_exception('phonealreadyverified', 'tool_phoneverification');
        } elseif ($recordexists && !$isverified) {
            throw new moodle_exception('otpforthisnumberalreadyexists', 'tool_phoneverification');
            throw new moodle_exception('wait10minutes', 'tool_phoneverification');
        } else {
            throw new moodle_exception('somethingwentwrong', 'tool_phoneverification');
        }
    } catch (moodle_exception $e) {
        // Catch the exception and add it to the array
        $responseobject->success = false;
        $responseobject->errormessage = $e->getMessage();
    }
    return $responseobject;
}

/**
 * Verifies an One-Time Password (OTP).
 *
 * @param string $otp The OTP to verify.
 * @return object
 */
function tool_phoneverification_verify_otp($otp) {
    global $DB, $USER, $SESSION;

    $responseobject = new stdClass();

    if (!isset($SESSION->otps)) {
        $SESSION->otps = new stdClass();
    }
    $dbcount = 0;
    $sessioncount = 0;
    $verified = 0;

    try {
        $sqlconditions = [
            'userid' => $USER->id,
        ];
        $sessionrecords = $SESSION->otps;
        $sessionasarray = get_object_vars($sessionrecords);

        if (!empty($sessionasarray)) {
            $sessioncount = 0;
            // This means there are 1 or 2 records in $SESSION->otps.
            foreach ($sessionrecords as $key => $record) {
                $sessioncount++;
                $expired = $record->expires <= time();
                $verified = $record->phoneisverified;
                $matches = password_verify($otp, $record->otp);
                if (!$expired && !$verified && $matches) {
                    $record->timeverified = time();
                    $record->phoneisverified = 1;
                    $DB->update_record('tool_phoneverification_otp', $record);
                    $responseobject->success = true;
                    $responseobject->tophonenumber = $record->tophonenumber;
                    return $responseobject;
                }
            }
        }

        // The DB will not be access if a session record was alread found by this point
        // because of the 'return' statement above.
        $dbrecords = $DB->get_records('tool_phoneverification_otp', $sqlconditions);

        if (!empty($dbrecords)) {
            // This means there are 1 or 2 records in $DB.
            foreach ($dbrecords as $key => $record) {
                $expired = $record->expires <= time();
                $verified = $record->phoneisverified;
                $matches = password_verify($otp, $record->otp);
                $responseobject->tophonenumber = $record->tophonenumber;
                if ($verified && $matches) {
                    $url = new moodle_url('/admin/tool/phoneverification/testoutgoingtextconf.php');
                    $link = html_writer::link($url, get_string('testoutgoingtextconf', 'tool_phoneverification'));
                    $responseobject->success = true;
                    $responseobject->successmessage = get_string('phonealreadyverified', 'tool_phoneverification');
                    return $responseobject;
                    return $responseobject;
                }
                if (!$expired && !$verified && $matches) {
                    $record->timeverified = time();
                    $record->phoneisverified = 1;
                    $DB->update_record('tool_phoneverification_otp', $record);
                    $responseobject->success = true;
                    return $responseobject;
                }
            }
        }
        if (($sessioncount == 1 || $dbcount == 1) && $verified == 1) {
            throw new moodle_exception('nophonestoverify', 'tool_phoneverification');
        } elseif ($sessioncount == 1 || $dbcount == 1) {
            throw new moodle_exception('otpdoesnotmatch', 'tool_phoneverification');
        } elseif ($sessioncount > 0 && $dbcount > 0) {
            throw new moodle_exception('otpsdonotmatch', 'tool_phoneverification');
        } else {
            $url = new moodle_url('/admin/tool/phoneverification/testoutgoingtextconf.php');
            $link = html_writer::link($url, get_string('testoutgoingtextconf', 'tool_phoneverification'));
            throw new moodle_exception('novalidotpsfound', 'tool_phoneverification', '', $link);
        }
    } catch (moodle_exception $e) {
        // Step 2: Catch the exception and add it to the array
        $responseobject->success = false;
        $responseobject->errormessage = $e->getMessage();
    }

    // OTP is valid and has not expired
    return $responseobject;
}
