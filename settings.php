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

    $ADMIN->add('server', new admin_category('phone', new lang_string('phone', 'tool_phoneverification')));
    $settingspage = new admin_settingpage('managetoolphoneverification', new lang_string('phoneproviderconfiguration', 'tool_phoneverification'));

    if ($ADMIN->fulltree) {

        // $settingspage->add(new admin_setting_configcheckbox(
        //     'tool_phoneverification/showinnavigation',
        //     new lang_string('showinnavigation', 'tool_phoneverification'),
        //     new lang_string('showinnavigation_desc', 'tool_phoneverification'),
        //     1
        // ));
        // $settingspage->add(new admin_setting_configselect(
        //     'tool_phoneverification/provider',
        //     new lang_string('provider', 'tool_phoneverification'),
        //     new lang_string('provider_desc', 'tool_phoneverification'),
        //     'infobip',
        //     array(
        //         'infobip' => 'Infobip',
        //         'twilio' => 'Twilio',
        //         'awssns' => 'AWS SNS'
        //     )
        // ));

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
            // '/^[a-f0-9]{32}-[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/',
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
            'testoutgoingtextc',
            new lang_string('testoutgoingtextconf', 'tool_phoneverification'),
            new lang_string('testoutgoingtextdetail', 'tool_phoneverification', $link)
        ));


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
    }

    $ADMIN->add('phone', $settingspage);
}

// // Email.
//     $ADMIN->add('phone', new admin_category('email', new lang_string('categoryemail', 'admin')));

//     // Outgoing mail configuration.
//     $temp = new admin_settingpage('outgoingmailconfig', new lang_string('outgoingmailconfig', 'admin'));

//     if (!empty($CFG->noemailever)) {
//         $noemaileverwarning = new \core\output\notification(get_string('noemaileverwarning', 'admin'),
//         \core\output\notification::NOTIFY_ERROR);
//         $temp->add(new admin_setting_heading('outgoingmaildisabled', '', $OUTPUT->render($noemaileverwarning)));
//     }

//     $temp->add(new admin_setting_heading('smtpheading', new lang_string('smtp', 'admin'),
//         new lang_string('smtpdetail', 'admin')));

//     $temp->add(new admin_setting_configtext('smtphosts', new lang_string('smtphosts', 'admin'),
//         new lang_string('configsmtphosts', 'admin'), '', PARAM_RAW));

//     $options = [
//         '' => new lang_string('none', 'admin'),
//         'ssl' => 'SSL',
//         'tls' => 'TLS',
//     ];

//     $temp->add(new admin_setting_configselect('smtpsecure', new lang_string('smtpsecure', 'admin'),
//         new lang_string('configsmtpsecure', 'admin'), '', $options));

//     $authtypeoptions = [
//         'LOGIN' => 'LOGIN',
//         'PLAIN' => 'PLAIN',
//         'NTLM' => 'NTLM',
//         'CRAM-MD5' => 'CRAM-MD5',
//     ];


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
