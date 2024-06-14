<?php
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    // INFOBIP_API_KEY setting
    $settings->add(new admin_setting_configtext(
        'phoneverification/infobip_api_key',
        get_string('infobip_api_key', 'phoneverification'),
        get_string('infobip_api_key_desc', 'phoneverification'),
        '',
        PARAM_ALPHANUMEXT
    ));

    // TWILIO_ACCOUNT_SID setting
    $settings->add(new admin_setting_configtext(
        'phoneverification/twilio_account_sid',
        get_string('twilio_account_sid', 'phoneverification'),
        get_string('twilio_account_sid_desc', 'phoneverification'),
        '',
        PARAM_ALPHANUMEXT
    ));

    // TWILIO_AUTH_TOKEN setting
    $settings->add(new admin_setting_configtext(
        'phoneverification/twilio_auth_token',
        get_string('twilio_auth_token', 'phoneverification'),
        get_string('twilio_auth_token_desc', 'phoneverification'),
        '',
        PARAM_ALPHANUMEXT
    ));

    // TWILIO_NUMBER setting
    $settings->add(new admin_setting_configtext(
        'phoneverification/twilio_number',
        get_string('twilio_number', 'phoneverification'),
        get_string('twilio_number_desc', 'phoneverification'),
        '',
        PARAM_ALPHANUMEXT
    ));
}
