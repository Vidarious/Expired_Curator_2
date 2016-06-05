<?php
/*
 * Variable file for Curator configuation data.
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator\Config;

    //*****
    //** Main Application Settings.
    //*****

    //Server path to where Curator is installed / extracted.
    define('Curator\Config\APPLICATION', TRUE);

    //Website administrator's contact e-mail.
    define('Curator\Config\APPLICATION\ADMIN_EMAIL', 'admin@example.com');

    //Curator's default timezone.
    define('Curator\Config\APPLICATION\TIMZEONE', 'America/New_York');

    //*****
    //** Pathing Settings
    //*****

    //Server path to where Curator is installed / extracted.
    define('Curator\Config\PATH\ROOT', htmlspecialchars($_SERVER["DOCUMENT_ROOT"]) . '/curator/'); //Cloud9

    //Directory path to where your homepage is located.
    define('Curator\Config\PATH\HOMEPAGE', '/');

    //Directory path to where Curator classes are located.
    define('Curator\Config\PATH\CLASSES', PATH\ROOT . 'resource/classes/');

    //Directory path to where Curator configuration files are located.
    define('Curator\Config\PATH\CONFIG', PATH\ROOT . 'resource/config/');

    //Directory path to where Curator language tables are located.
    define('Curator\Config\PATH\LANG', PATH\ROOT . 'resource/language');

    //Directory path to where Curator log files are located.
    define('Curator\Config\PATH\LOG\ERROR', PATH\ROOT . 'application/logs/errors.log');
    define('Curator\Config\PATH\LOG\HAZARD', PATH\ROOT . 'application/logs/hazards.log');

    //Directory path to where Curator warning log file is located.
    define('Curator\Config\PATH\LOG\WARNING', PATH\ROOT . 'application/logs/warnings.log');

    //Directory path to where Curator forms.
    define('Curator\Config\PATH\FORMS', PATH\ROOT . 'resource/forms/');
    //*****
    //** Language Settings.
    //*****

    //Curator language.
    define('Curator\Config\LANG\CURATOR_USER_DEFAULT', 'en_CA');
    define('Curator\Config\LANG\CURATOR_APPLICATION', 'en_CA');

    //*****
    //** Database Settings.
    //*****

    //Host address for SQL database.
    define('Curator\Config\DB\HOST', getenv('IP')); //Cloud9

    //Database name for SQL database.
    define('Curator\Config\DB\NAME', 'Curator');
    //define('Curator\Config\DB\NAME', 'sql5103939');

    //Username for SQL database.
    define('Curator\Config\DB\USER', 'Curator');

    //Password for SQL database.
    define('Curator\Config\DB\PASS', 'myPassword123'); //OK for GIT
    //define('Curator\Config\DB\USER', 'sql5103939');

    //*****
    //** Session Settings.
    //*****

    //Session name.
    define('Curator\Config\SESSION\NAME', 'Curator_Session');

    //Site unique identifier.
    define('Curator\Config\SESSION\IDENTIFIER', '!)1@(4#*$&5%^AoB3br3kZ1');

    //Session timeout period (seconds).
    define('Curator\Config\SESSION\TIMEOUT', '600');

    //Session encryption algorithm.
    define('Curator\Config\SESSION\ENCRYPTION', 'sha512');

    //Setting for enforcing consistant IP for sessions. May cause issue with TOR users. 1: Enforce 0: Disable.
    define('Curator\Config\SESSION\ENFORCE_IP', TRUE);

    //Setting for enforcing consistant IP for sessions. May cause issue with TOR users. 1: Enforce 0: Disable.
    define('Curator\Config\SESSION\ENFORCE_USERAGENT', TRUE);

    //Session regenerate time.
    define('Curator\Config\SESSION\REGENERATE\TIME\ENFORCE', TRUE);
    define('Curator\Config\SESSION\REGENERATE\TIME', '300');

    //Session regenerate %. Value should be 1-100.
    define('Curator\Config\SESSION\REGENERATE\PERCENT\ENFORCE', TRUE);
    define('Curator\Config\SESSION\REGENERATE\PERCENT', '5');

    //*****
    //** Cookie Settings.
    //*****

    //Cookie path ownership. '/' is default for entire site.
    define('Curator\Config\COOKIE\PATH', '/');

    //Cookie domain path. $_SERVER['SERVER_NAME'] is default for entire site.
    define('Curator\Config\COOKIE\DOMAIN', htmlspecialchars($_SERVER['SERVER_NAME']));

    //*****
    //** Form Settings.
    //*****

    define('Curator\Config\FORM\RECAPTCHA', '0');
    define('Curator\Config\FORM\RECAPTCHA\SECRET', '6LcqUxkTAAAAALvmj2I6BM0iNIHJavNWd_MIQyh6');

    //*****
    //** Account Policy Settings.
    //*****

    //Minimun time that must pass before another new account can be created.
    define('Curator\Config\ACCOUNT\FLOOD_DELAY', '60');

    //Account field setting: Username.
    define('Curator\Config\ACCOUNT\FIELD\USERNAME', '1');
    define('Curator\Config\ACCOUNT\FIELD\USERNAME\REQUIRED', '1');

    //Account field setting: Given Name.
    define('Curator\Config\ACCOUNT\FIELD\GIVEN_NAME', '1');
    define('Curator\Config\ACCOUNT\FIELD\GIVEN_NAME\REQUIRED', '1');

    //Account field setting: Family Name.
    define('Curator\Config\ACCOUNT\FIELD\FAMILY_NAME', '1');
    define('Curator\Config\ACCOUNT\FIELD\FAMILY_NAME\REQUIRED', '1');

    //Account field setting: Preferred Name.
    define('Curator\Config\ACCOUNT\FIELD\PREFERRED_NAME', '1');
    define('Curator\Config\ACCOUNT\FIELD\PREFERRED_NAME\REQUIRED', '1');

    //Account field setting: Title.
    define('Curator\Config\ACCOUNT\FIELD\TITLE', '1');
    define('Curator\Config\ACCOUNT\FIELD\TITLE\REQUIRED', '1');

    //Account field setting: Gender.
    define('Curator\Config\ACCOUNT\FIELD\GENDER', '1');
    define('Curator\Config\ACCOUNT\FIELD\GENDER\REQUIRED', '1');

    //Account field setting: Date of Birth.
    define('Curator\Config\ACCOUNT\FIELD\DATE_OF_BIRTH', '1');
    define('Curator\Config\ACCOUNT\FIELD\DATE_OF_BIRTH\REQUIRED', '1');

    //Account field setting: Phone.
    define('Curator\Config\ACCOUNT\FIELD\PHONE', '1');
    define('Curator\Config\ACCOUNT\FIELD\PHONE\REQUIRED', '1');

    //Account field setting: Address.
    define('Curator\Config\ACCOUNT\FIELD\ADDRESS', '1');
    define('Curator\Config\ACCOUNT\FIELD\ADDRESS\LINE_1\REQUIRED', '1');
    define('Curator\Config\ACCOUNT\FIELD\ADDRESS\CITY\REQUIRED', '1');
    define('Curator\Config\ACCOUNT\FIELD\ADDRESS\PROVINCE\REQUIRED', '1');
    define('Curator\Config\ACCOUNT\FIELD\ADDRESS\POSTAL\REQUIRED', '1');
    define('Curator\Config\ACCOUNT\FIELD\ADDRESS\COUNTRY\REQUIRED', '1');

    //Account password settings.
    define('Curator\Config\ACCOUNT\FIELD\SETTING\PASSWORD\MIN_LENGTH', '5');
    define('Curator\Config\ACCOUNT\FIELD\SETTING\PASSWORD\MAX_LENGTH', '50');
    define('Curator\Config\ACCOUNT\FIELD\SETTING\PASSWORD\UPPER_CHAR', '1');
    define('Curator\Config\ACCOUNT\FIELD\SETTING\PASSWORD\LOWER_CHAR', '1');
    define('Curator\Config\ACCOUNT\FIELD\SETTING\PASSWORD\SPECIAL_CHAR', '1');
    define('Curator\Config\ACCOUNT\FIELD\SETTING\PASSWORD\NUMBER', '1');
    define('Curator\Config\ACCOUNT\FIELD\SETTING\PASSWORD\WORD', '1');

    //Display specific policy errors or not. Not = Generic message.
    define('Curator\Config\ACCOUNT\FIELD\SETTING\PASSWORD\DISPLAY', '1');

    //Password encryption settings.
    define('Curator\Config\ACCOUNT\FIELD\SETTING\PASSWORD\ENCRYPTION', 'PASSWORD_BCRYPT');

    //BCrypt encryption cost range: 4 - 31.
    define('Curator\Config\ACCOUNT\FIELD\SETTING\PASSWORD\ENCRYPTION\BCRYPT\COST', '10');

    //Account username settings
    define('Curator\Config\ACCOUNT\FIELD\SETTING\USERNAME\MIN_LENGTH', '3');
    define('Curator\Config\ACCOUNT\FIELD\SETTING\USERNAME\MAX_LENGTH', '25');
?>
