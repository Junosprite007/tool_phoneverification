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

require __DIR__ . "/vendor/autoload.php";
require_once($CFG->libdir . '/filelib.php');
// require_once($CFG->libdir . '/http.php');

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
 * @param string $phonenumber The cell phone number to validate.
 * @param string $country The country code to use.
 * @return boolean
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

function tool_phoneverification_send_sms($provider, $tonumber, $message) {
    global $CFG;
    global $SITE;

    $responseobject = new stdClass();

    switch ($provider) {
        case 'infobip':
            try {
                $infobipapikey = get_config('tool_phoneverification', 'infobipapikey');
                $infobipapibaseurl = get_config('tool_phoneverification', 'infobipapibaseurl');
                $configuration = new Configuration(host: $infobipapibaseurl, apiKey: $infobipapikey);
                $api = new SmsApi(config: $configuration);
                $destination = new SmsDestination(to: $tonumber);
                $message = new SmsTextualMessage(
                    destinations: [$destination],
                    text: $message,
                    from: $SITE->shortname
                );

                // $request = new SmsAdvancedTextualRequest(messages: [$message]);
                // $response = $api->sendSmsMessage($request);
                // $responseobject->response = $response;
                $responseobject->response = 'Confirmed!'; // This is just for testing OPT Code verifictaion.
            } catch (Exception $e) {
                // Handle the exception
                $response = 'Caught exception: ' . $e->getMessage() . "\n";
                $responseobject->response = $response;
                $responseobject->error = $e->getMessage();
            }

            // var_dump('$response->getRequestError(): ');
            // echo "<pre>";
            // var_dump($response->getRequestError());
            // echo "</pre>";
            // echo "<br />";
            // echo "<br />";
            // Straight from InfoBip's website.
            // try {
            //     // if ($response->getStatus() == 200) {
            //     //     echo $response->getBody();
            //     // } else {
            //     //     echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
            //     //         $response->getReasonPhrase();
            //     // }
            // } catch ($message $e) {
            //     echo 'Error: ' . $e->getMessage();
            // }

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

// /**
//  * Validates an email to make sure it makes sense.
//  *
//  * @param string $address The email address to validate.
//  * @return boolean
//  */
// function validate_text($address) {
//     global $CFG;

//     if ($address === null || $address === false || $address === '') {
//         return false;
//     }

//     require_once("{$CFG->libdir}/phpmailer/moodle_phpmailer.php");

//     return moodle_phpmailer::validateAddress($address ?? '') && !preg_match('/[<>]/', $address);
// }
