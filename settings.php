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
    $ADMIN->add('server', new admin_externalpage(
        'testoutgoingtextconf',
        new lang_string('testoutgoingtextconf', 'tool_phoneverification'),
        new moodle_url('/admin/testoutgoingtextconf.php'),
        'moodle/site:config',
        true
    ));
    $ADMIN->add('server', new admin_externalpage(
        'verifyotp',
        new lang_string('verifyotp', 'tool_phoneverification'),
        new moodle_url('/admin/verifyotp.php'),
        'moodle/site:config',
        true
    ));

    $ADMIN->add('server', new admin_category('phone', new lang_string('phone', 'tool_phoneverification')));
    $settingspage = new admin_settingpage('managetoolphoneverification', new lang_string('phoneproviderconfiguration', 'tool_phoneverification'));

    if ($ADMIN->fulltree) {

        // Infobip
        $link = html_writer::link('https://portal.infobip.com/', get_string('here', 'tool_phoneverification'));
        $settingspage->add(new admin_setting_heading(
            'infobip',
            new lang_string('infobip', 'tool_phoneverification'),
            new lang_string('infobip_desc', 'tool_phoneverification', $link)
        ));
        $settingspage->add(new admin_setting_configtext(
            'tool_phoneverification/infobipapikey',
            new lang_string('infobipapikey', 'tool_phoneverification'),
            new lang_string('infobipapikey_desc', 'tool_phoneverification'),
            '',
            PARAM_TEXT,
            69
        ));
        $settingspage->add(new admin_setting_configtext(
            'tool_phoneverification/infobipapibaseurl',
            new lang_string('infobipapibaseurl', 'tool_phoneverification'),
            new lang_string('infobipapibaseurl_desc', 'tool_phoneverification'),
            '',
            PARAM_URL
        ));

        // // Twilio
        // $link = html_writer::link('https://www.twilio.com/', get_string('here', 'tool_phoneverification'));
        // $settingspage->add(new admin_setting_heading(
        //     'twilio',
        //     new lang_string('twilio', 'tool_phoneverification'),
        //     new lang_string('twilio_desc', 'tool_phoneverification', $link)
        // ));
        // $settingspage->add(new admin_setting_configtext(
        //     'tool_phoneverification/twilioaccountsid',
        //     new lang_string('twilioaccountsid', 'tool_phoneverification'),
        //     new lang_string('twilioaccountsid_desc', 'tool_phoneverification'),
        //     '',
        //     PARAM_TEXT
        //     // '/^[a-f0-9]{32}-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/',
        //     // 69
        // ));
        // $settingspage->add(new admin_setting_configtext(
        //     'tool_phoneverification/twilioauthtoken',
        //     new lang_string('twilioauthtoken', 'tool_phoneverification'),
        //     new lang_string('twilioauthtoken_desc', 'tool_phoneverification'),
        //     '',
        //     PARAM_URL
        // ));
        // $settingspage->add(new admin_setting_configtext(
        //     'tool_phoneverification/twilionumber',
        //     new lang_string('twilionumber', 'tool_phoneverification'),
        //     new lang_string('twilionumber_desc', 'tool_phoneverification'),
        //     '',
        //     PARAM_TEXT
        // ));

        // // AWS SNS
        // $link = html_writer::link('https://aws.amazon.com/sns/', get_string('here', 'tool_phoneverification'));
        // $settingspage->add(new admin_setting_heading(
        //     'awssns',
        //     new lang_string('awssns', 'tool_phoneverification'),
        //     new lang_string('awssns_desc', 'tool_phoneverification', $link)
        // ));
        // $settingspage->add(new admin_setting_configtext(
        //     'tool_phoneverification/awssnsaccesskey',
        //     new lang_string('awssnsaccesskey', 'tool_phoneverification'),
        //     new lang_string('awssnsaccesskey_desc', 'tool_phoneverification'),
        //     '',
        //     PARAM_TEXT,
        //     69
        // ));
        // $settingspage->add(new admin_setting_configtext(
        //     'tool_phoneverification/awssnssecretkey',
        //     new lang_string('awssnssecretkey', 'tool_phoneverification'),
        //     new lang_string('awssnssecretkey_desc', 'tool_phoneverification'),
        //     '',
        //     PARAM_TEXT,
        //     69
        // ));
        // $settingspage->add(new admin_setting_configtext(
        //     'tool_phoneverification/awssnsregion',
        //     new lang_string('awssnsregion', 'tool_phoneverification'),
        //     new lang_string('awssnsregion_desc', 'tool_phoneverification'),
        //     '',
        //     PARAM_TEXT,
        //     69
        // ));

        // Test outgoing text configuration.
        $url = new moodle_url('/admin/tool/phoneverification/testoutgoingtextconf.php');
        $link = html_writer::link($url, get_string('testoutgoingtextconf', 'tool_phoneverification'));
        $settingspage->add(new admin_setting_heading(
            'testoutgoingtextconf',
            new lang_string('testoutgoingtextconf', 'tool_phoneverification'),
            new lang_string('testoutgoingtextdetail', 'tool_phoneverification', $link)
        ));

        // Verify OTP.
        $url = new moodle_url('/admin/tool/phoneverification/verifyotp.php');
        $link = html_writer::link($url, get_string('verifyotp', 'tool_phoneverification'));
        $settingspage->add(new admin_setting_heading(
            'verifyotp',
            new lang_string('verifyotp', 'tool_phoneverification'),
            new lang_string('verifyotpdetail', 'tool_phoneverification', $link)
        ));
    }

    $ADMIN->add('phone', $settingspage);
}
