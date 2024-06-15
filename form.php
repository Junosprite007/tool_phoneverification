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
 * @copyright   2024 Joshua Kirby <josh@funlearningcompany.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
global $DB, $OUTPUT, $PAGE;


$PAGE->set_context(context_system::instance());
$PAGE->set_url('/tool/phoneverification/form.php');
$PAGE->set_title('PHP SMS');
$PAGE->set_heading('PHP SMS');

echo $OUTPUT->header();

$provider = get_config('tool_phoneverification', 'provider');
$infobipapikey = get_config('tool_phoneverification', 'infobipapikey');
$infobipapibaseurl = get_config('tool_phoneverification', 'infobipapibaseurl');

echo $provider . "<br>";
echo $infobipapikey . "<br>";
echo $infobipapibaseurl;

?>
<form method="post" action="send.php">
    <label for="number">Number</label>
    <input type="text" name="number" id="number" />
    <label for="message">Message</label>
    <textarea name="message" id="message"></textarea>
    <fieldset>
        <legend>Provider</legend>
        <label>
            <input type="radio" name="provider" value="infobip" checked /> Infobip
        </label>
        <br />
        <label>
            <input type="radio" name="provider" value="twilio" /> Twilio
        </label>
        <br />
        <label>
            <input type="radio" name="provider" value="awssns" /> AWS SNS
        </label>
    </fieldset>
    <button>Send</button>
</form>
<?php
echo $OUTPUT->footer();
