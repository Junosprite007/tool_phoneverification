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

use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;
use Twilio\Rest\Client;

require_once(__DIR__ . "/vendor/autoload.php");
require_once($CFG->libdir . '/filelib.php');
// require_once($CFG->libdir . '/http.php');
// require_once('HTTP/Request2.php');

/**
 * Validates a cell phone number to make sure it makes sense.
 *
 * @param string $phonenumber The cell phone number to validate.
 * @param string $country The country code to use.
 * @return boolean
 */
function tool_phoneverification_validate_phone_number($phonenumber, $country = 'US') {
    // Remove commonly used characters from the phone number that are not numbers: ().-+ and the white space char.
    $parsedphonenumber = preg_replace("/[\(\)\-\s+\.]/", "", $phonenumber);

    try {
        if (!ctype_digit($parsedphonenumber)) {
            throw new \Exception("Invalid phone number format. We currently only accept U.S. numbers. You can use most standard ways of typing a phone number.");
        }
    } catch (\Exception $e) {
        // return $e;
        return $e->getMessage();
    }

    switch ($country) {
        case 'US':
            // Check if the number is not empty, if it only contains digits, and if it is a valid 10 or 11 digit United States phone number.
            try {
                if ((strlen($parsedphonenumber) == 10) && $phonenumber[0] != 1) {
                    // echo "10 digits.";
                    $parsedphonenumber = "+1" . $parsedphonenumber;
                } elseif ((strlen($parsedphonenumber) == 11) && $phonenumber[0] == 1) {
                    // echo "11 digits.";
                    $parsedphonenumber = "+" . $parsedphonenumber;
                } else {
                    throw new \Exception(new lang_string('invalidphonenumber', 'tool_phoneverification') . new lang_string('wecurrentlyonlyacceptusnumbers', 'tool_phoneverification'));
                }
            } catch (\Exception $e) {
                // return $e;
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
 * @param object $allphoneconfigs An object containing all the phone configuration settings for every provider.
 * @return array
 */
function tool_phoneverification_providers_to_show($allphoneconfigs) {
    // foreach ($allphoneconfigs as $key => $value) {
    // }

    $providers = [];

    if ($allphoneconfigs->awssnsaccesskey && $allphoneconfigs->awssnssecretkey && $allphoneconfigs->awssnsregion) {
        $providers['awssns'] = get_string('awssns', 'tool_phoneverification');
    }
    if ($allphoneconfigs->infobipapikey && $allphoneconfigs->infobipapibaseurl) {
        $providers['infobip'] = get_string('infobip', 'tool_phoneverification');
    }
    if ($allphoneconfigs->twilioaccountsid && $allphoneconfigs->twilioauthtoken && $allphoneconfigs->twilionumber) {
        $providers['twilio'] = get_string('twilio', 'tool_phoneverification');
    }

    // $providers = [
    //     'awssns' => get_string('awssns', 'tool_phoneverification'),
    //     'infobip' => get_string('infobip', 'tool_phoneverification'),
    //     'twilio' => get_string('twilio', 'tool_phoneverification')
    // ];



    // echo '<br>';
    // echo '<br>';
    // echo '<br>';
    // var_dump("\$allphoneconfigs from lib: ");
    // var_dump($allphoneconfigs->infobipapikey); // this works.
    // echo '<br>';
    // echo '<br>';
    // var_dump('$providers: '); // this works.
    // var_dump($providers); // this works.
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
    global $CFG;
    global $SITE;

    $responseobject = new stdClass();
    $otp = rand(100000, 999999);

    switch ($provider) {
        case 'infobip':
            try {
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
                }
            } catch (Exception $e) {
                // Handle the exception
                $responseobject->errormessage = $e->getMessage();
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
    // return '$response';
    return $responseobject;
}

// // This is the old version of the function.
// /**
//  * Sends an SMS message to a phone number.
//  *
//  * @param string $provider The provider to use for sending the SMS message.
//  * @param string $tonumber The phone number to send the SMS message to.
//  * @param string $message The message to send in the SMS message.
//  * @return object
//  */
// function tool_phoneverification_send_sms($provider, $tonumber, $message) {
//     global $CFG;
//     global $SITE;

//     $responseobject = new stdClass();

//     switch ($provider) {
//         case 'infobip':
//             try {
//                 $infobipapikey = get_config('tool_phoneverification', 'infobipapikey');
//                 $infobipapibaseurl = get_config('tool_phoneverification', 'infobipapibaseurl');
//                 $configuration = new Configuration(host: $infobipapibaseurl, apiKey: $infobipapikey);
//                 $api = new SmsApi(config: $configuration);
//                 $destination = new SmsDestination(to: $tonumber);
//                 $msg = new SmsTextualMessage(
//                     destinations: [$destination],
//                     text: $message,
//                     from: $SITE->shortname
//                 );

//                 // $request = new SmsAdvancedTextualRequest(messages: [$msg]);
//                 // $response = $api->sendSmsMessage($request);
//                 // $responseobject->response = $response;
//                 $responseobject->response = 'Confirmed!'; // This is just for testing OPT Code verifictaion.
//             } catch (Exception $e) {
//                 // Handle the exception
//                 $response = 'Caught exception: ' . $e->getMessage() . "\n";
//                 $responseobject->response = $response;
//                 $responseobject->error = $e->getMessage();
//             }

//             // var_dump('$response->getRequestError(): ');
//             // echo "<pre>";
//             // var_dump($response->getRequestError());
//             // echo "</pre>";
//             // echo "<br />";
//             // echo "<br />";
//             // Straight from InfoBip's website.
//             // try {
//             //     // if ($response->getStatus() == 200) {
//             //     //     echo $response->getBody();
//             //     // } else {
//             //     //     echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
//             //     //         $response->getReasonPhrase();
//             //     // }
//             // } catch ($message $e) {
//             //     echo 'Error: ' . $e->getMessage();
//             // }

//             break;
//         case 'twilio':
//             // $twilioaccountsid = get_config('tool_phoneverification', 'twilioaccountsid');
//             // $twilioauthtoken = get_config('tool_phoneverification', 'twilioauthtoken');
//             // $twilionumber = get_config('tool_phoneverification', 'twilionumber');

//             break;
//         case 'awssns':
//             // $awssnsaccesskey = get_config('tool_phoneverification', 'awssnsaccesskey');
//             // $awssnssecretkey = get_config('tool_phoneverification', 'awssnssecretkey');
//             // $awssnsregion = get_config('tool_phoneverification', 'awssnsregion');

//             break;
//         default:
//             break;
//     }
//     // return '$response';
//     return $responseobject;
// }

/**
 * Sends an SMS message to a phone number via POST and HTTPS.
 *
 * @param string $provider The provider to use for sending the SMS message.
 * @param string $tonumber The phone number to send the SMS message to.
 * @param string $message The message to send in the SMS message.
 * @return object
 */
function tool_phoneverification_send_secure_otp($provider, $tonumber) {
    global $CFG, $SITE, $DB, $USER, $SESSION;

    $responseobject = new stdClass();
    $otp = mt_rand(100000, 999999);
    $message = get_string('phoneverificationcodeforflip', 'tool_phoneverification', $otp);

    // Store OTP in session
    $SESSION->otp = $otp;

    // Store hashed OTP in database with expiration time
    $record = new stdClass();
    $record->userid = $USER->id;
    $record->otp = password_hash($otp, PASSWORD_DEFAULT);  // Hash the OTP
    $record->timecreated = time();
    $record->expires = time() + 120;  // OTP expires after 5 minutes

    $DB->insert_record('tool_phoneverification_otp', $record);

    echo '<br>';
    echo '<br>';
    echo '<br>';
    echo $otp;
    echo '<br>';
    echo '<br>';

    switch ($provider) {
        case 'infobip':
            try {
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


                // Just for testing.
                $responseobject->success = true;

                // Uncomment the following when you're ready to test for real.

                //     // Make the request
                //     $responseobject->response = $curl->post('https://' . $infobipapibaseurl . '/sms/2/text/advanced', $postdata);

                //     // Get the HTTP response code
                //     $info = $curl->get_info();
                //     $responseobject->errormessage = '';
                //     $responseobject->errorobject = new stdClass();

                //     if ($info['http_code'] >= 200 && $info['http_code'] < 300) {
                //         // The request was successful
                //         $responseobject->success = true;
                //     } else {
                //         // The request failed
                //         $responseobject->errorobject->httpcode = $info['http_code'];
                //         $responseobject->errorobject->curlcode = $curl->get_errno();
                //         $responseobject->errormessage = get_string('httprequestfailedwithcode', 'tool_phoneverification', $responseobject->errorobject);
                //         $responseobject->success = false;
                //     }
            } catch (Exception $e) {
                // Handle the exception
                $responseobject->errormessage = $e->getMessage();
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
    // return '$response';
    return $responseobject;
}

/**
 * Sends an SMS message to a phone number via POST and HTTPS.
 *
 * @param string $otp The OTP to verify.
 * @return object
 */
function tool_phoneverification_verify_otp($otp) {
    global $DB, $USER;

    // Retrieve the OTP record from the database
    $record = $DB->get_record('tool_phoneverification_otp', array('userid' => $USER->id));

    // Check if the OTP has expired
    if (time() > $record->expires) {
        throw new moodle_exception('OTP has expired');
    }

    // Verify the OTP
    if (!password_verify($otp, $record->otp)) {
        throw new moodle_exception('Invalid OTP');
    }

    // OTP is valid and has not expired
    return true;
}
