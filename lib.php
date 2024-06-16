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

/**
 * Validates a cell phone number to make sure it makes sense.
 *
 * @param string $phonenumber The cell phone number to validate.
 * @return boolean
 */
function tool_phoneverification_validate_phone_number($phonenumber) {
    // Add your phone number validation logic here.
    // This is a simple example that checks if the number is not empty and if it only contains digits.
    return !empty($phonenumber) && ctype_digit($phonenumber);
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
