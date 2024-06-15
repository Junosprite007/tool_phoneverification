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
 * Phone verification settings.
 *
 * @package     tool_phoneverification
 * @copyright   2024 onwards Joshua Kirby <josh@funlearningcompany.com>
 * @author      Joshua Kirby
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;


if ($hassiteconfig) {
    $ADMIN->add('server', new admin_category('tool_phoneverification_settings', new lang_string('pluginname', 'tool_phoneverification')));
    $settingspage = new admin_settingpage('managetoolphoneverification', new lang_string('manage', 'tool_phoneverification'));

    if ($ADMIN->fulltree) {
        // $settingspage->add(new admin_setting_configcheckbox(
        //     'tool_phoneverification/showinnavigation',
        //     new lang_string('showinnavigation', 'tool_phoneverification'),
        //     new lang_string('showinnavigation_desc', 'tool_phoneverification'),
        //     1
        // ));
        $settingspage->add(new admin_setting_configselect(
            'tool_phoneverification/provider',
            new lang_string('provider', 'tool_phoneverification'),
            new lang_string('provider_desc', 'tool_phoneverification'),
            'infobip',
            array(
                'infobip' => 'Infobip',
                'twilio' => 'Twilio',
                'awssns' => 'AWS SNS'
            )
        ));
        $settingspage->add(new admin_setting_configtext(
            'tool_phoneverification/infobipapikey',
            new lang_string('infobipapikey', 'tool_phoneverification'),
            new lang_string('infobipapikey_desc', 'tool_phoneverification'),
            '',
            PARAM_TEXT,
            // '/^[a-f0-9]{32}-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/',
            69
        ));
        $settingspage->add(new admin_setting_configtext(
            'tool_phoneverification/infobipapibaseurl',
            new lang_string('infobipapibaseurl', 'tool_phoneverification'),
            new lang_string('infobipapibaseurl_desc', 'tool_phoneverification'),
            '',
            PARAM_URL,
            // '/^[a-f0-9]{32}-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/',
            69
        ));
    }

    // $settingspage->add(new admin_setting_config(
    //     'tool_phoneverification/testinputs',
    //     new lang_string('testinputs', 'tool_phoneverification'),
    //     new lang_string('testinputs_desc', 'tool_phoneverification'),
    //     new moodle_url('/tool/phoneverification/testinputs.php')
    // ));

    $ADMIN->add('toolplugins', $settingspage);
}

// if ($ADMIN->fulltree) {
//     // INFOBIP_API_KEY setting.
//     $settings->add(new admin_setting_configtext(
//         'phoneverification/infobip_api_key',
//         get_string('infobip_api_key', 'phoneverification'),
//         get_string('infobip_api_key_desc', 'phoneverification'),
//         '',
//         PARAM_ALPHANUMEXT
//     ));

//     // TWILIO_ACCOUNT_SID setting.
//     $settings->add(new admin_setting_configtext(
//         'phoneverification/twilio_account_sid',
//         get_string('twilio_account_sid', 'phoneverification'),
//         get_string('twilio_account_sid_desc', 'phoneverification'),
//         '',
//         PARAM_ALPHANUMEXT
//     ));

//     // TWILIO_AUTH_TOKEN setting.
//     $settings->add(new admin_setting_configtext(
//         'phoneverification/twilio_auth_token',
//         get_string('twilio_auth_token', 'phoneverification'),
//         get_string('twilio_auth_token_desc', 'phoneverification'),
//         '',
//         PARAM_ALPHANUMEXT
//     ));

//     // TWILIO_NUMBER setting.
//     $settings->add(new admin_setting_configtext(
//         'phoneverification/twilio_number',
//         get_string('twilio_number', 'phoneverification'),
//         get_string('twilio_number_desc', 'phoneverification'),
//         '',
//         PARAM_ALPHANUMEXT
//     ));
// }
