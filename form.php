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
 * @package     local_phoneverification
 * @category    string
 * @copyright   2024 Joshua Kirby <josh@funlearningcompany.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
global $DB, $OUTPUT, $PAGE;

$PAGE->set_context(context_system::instance());
$PAGE->set_title('PHP SMS');
$PAGE->set_heading('PHP SMS');

echo $OUTPUT->header();

echo '<form method="post" action="send.php">';
echo '<label for="number">Number</label>';
echo '<input type="text" name="number" id="number" />';
echo '<label for="message">Message</label>';
echo '<textarea name="message" id="message"></textarea>';
echo '<fieldset>';
echo '<legend>Provider</legend>';
echo '<label>';
echo '<input type="radio" name="provider" value="infobip" checked /> Infobip';
echo '</label>';
echo '<br />';
echo '<label>';
echo '<input type="radio" name="provider" value="twilio" /> Twilio';
echo '</label>';
echo '<br />';
echo '<label>';
echo '<input type="radio" name="provider" value="awssns" /> AWS SNS';
echo '</label>';
echo '</fieldset>';
echo '<button>Send</button>';
echo '</form>';

echo $OUTPUT->footer();
