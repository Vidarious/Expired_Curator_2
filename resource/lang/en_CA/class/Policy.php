<?php
/*
 * en_CA language file for Curator Field class. Contains all user visable messaging.
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator\Classes\Language\Policy;

    //Deny direct access to file.
    if(!defined('Curator\Config\APPLICATION'))
    {
        header("Location: " . "http://" . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
        die();
    }

    use Curator\Config\ACCOUNT\FIELD\SETTING AS SETTING;

    define('Curator\Classes\Language\Policy\EMAIL\MISSING', 'E-mail is required.');
    define('Curator\Classes\Language\Policy\EMAIL\INVALID', 'Invalid e-mail address.');

    define('Curator\Classes\Language\Policy\EMAIL_CONFIRM\MISMATCH', 'E-mail addresses do not match.');

    define('Curator\Classes\Language\Policy\PASSWORD\MISSING', 'Password is required.');
    define('Curator\Classes\Language\Policy\PASSWORD\POLICY', 'Password does not meet requirements.');

    define('Curator\Classes\Language\Policy\PASSWORD_CONFIRM\MISMATCH', 'Passwords do not match.');

    define('Curator\Classes\Language\Policy\PASSWORD\POLICY\SHOW\GENERAL', 'Password Policy:');
    define('Curator\Classes\Language\Policy\PASSWORD\POLICY\SHOW\LENGTH', 'Must contain between ' . SETTING\PASSWORD\MIN_LENGTH . ' and ' . SETTING\PASSWORD\MAX_LENGTH . ' characters in length');
    define('Curator\Classes\Language\Policy\PASSWORD\POLICY\SHOW\UPPER_CHAR', 'Must contain at least ' . SETTING\PASSWORD\UPPER_CHAR . ' upper-case character(s)');
    define('Curator\Classes\Language\Policy\PASSWORD\POLICY\SHOW\LOWER_CHAR', 'Must contain at least ' . SETTING\PASSWORD\LOWER_CHAR . ' lower-case character(s)');
    define('Curator\Classes\Language\Policy\PASSWORD\POLICY\SHOW\SPECIAL_CHAR', 'Must contain at least ' . SETTING\PASSWORD\SPECIAL_CHAR . ' special character(s)');
    define('Curator\Classes\Language\Policy\PASSWORD\POLICY\SHOW\NUMBER_CHAR', 'Must contain at least ' . SETTING\PASSWORD\NUMBER . ' number(s)');

    define('Curator\Classes\Language\Policy\PASSWORD\POLICY\SHOW\RESTRICTED_WORD', 'Your password contains a group of restricted characters: ');

    define('Curator\Classes\Language\Policy\USERNAME\MISSING', 'Username is required.');
    define('Curator\Classes\Language\Policy\USERNAME\POLICY\LENGTH', 'Must contain between ' . SETTING\USERNAME\MIN_LENGTH . ' and ' . SETTING\USERNAME\MAX_LENGTH . ' alphanumeric characters in length');
    define('Curator\Classes\Language\Policy\USERNAME\POLICY\INVALID', 'Username does not meet requirements. Only alphanumeric characters are allowed.');

    define('Curator\Classes\Language\Policy\GIVEN_NAME\MISSING', 'Given name is required.');
    define('Curator\Classes\Language\Policy\GIVEN_NAME\POLICY\INVALID', 'Given name does not meet requirements. Only alphabet characters are allowed.');
    define('Curator\Classes\Language\Policy\GIVEN_NAME\POLICY\LENGTH', 'Given name is too long.');

    define('Curator\Classes\Language\Policy\FAMILY_NAME\MISSING', 'Family name is required.');
    define('Curator\Classes\Language\Policy\FAMILY_NAME\POLICY\INVALID', 'Family name does not meet requirements. Only alphabet characters are allowed.');
    define('Curator\Classes\Language\Policy\FAMILY_NAME\POLICY\LENGTH', 'Family name is too long.');

    define('Curator\Classes\Language\Policy\PREFERRED_NAME\MISSING', 'Preffered name is required.');
    define('Curator\Classes\Language\Policy\PREFERRED_NAME\POLICY\INVALID', 'Preffered name does not meet requirements. Only alphabet characters are allowed.');
    define('Curator\Classes\Language\Policy\PREFERRED_NAME\POLICY\LENGTH', 'Preffered name is too long.');

    define('Curator\Classes\Language\Policy\TITLE\MISSING', 'Title is required.');
    define('Curator\Classes\Language\Policy\TITLE\POLICY\INVALID', 'Title does not meet requirements. Only alphabet characters are allowed.');
    define('Curator\Classes\Language\Policy\TITLE\POLICY\LENGTH', 'Title is too long.');

    define('Curator\Classes\Language\Policy\GENDER\MISSING', 'Gender is required.');
    define('Curator\Classes\Language\Policy\GENDER\POLICY\INVALID', 'Gender does not meet requirements. Only alphabet characters are allowed.');
    define('Curator\Classes\Language\Policy\GENDER\POLICY\LENGTH', 'Gender is too long.');

    define('Curator\Classes\Language\Policy\DATE_OF_BIRTH\MISSING', 'Date of birth is required.');
    define('Curator\Classes\Language\Policy\DATE_OF_BIRTH\POLICY\INVALID', 'Date of birth does not meet requirements. Please use the format: MM/DD/YYYY');
    define('Curator\Classes\Language\Policy\DATE_OF_BIRTH\POLICY\BAD_DATE', 'Date of birth is an invalid date.');

    define('Curator\Classes\Language\Policy\PHONE\MISSING', 'Phone number is required.');
    define('Curator\Classes\Language\Policy\PHONE\POLICY\INVALID', 'Phone number does not meet requirements. Only numbers & characters are allowed.');
    define('Curator\Classes\Language\Policy\PHONE\POLICY\LENGTH', 'Phone number is too long.');

    define('Curator\Classes\Language\Policy\ADDRESS_LABEL\POLICY\INVALID', 'Address Label does not meet requirements. Only letters and numbers characters are allowed.');
    define('Curator\Classes\Language\Policy\ADDRESS_LABEL\POLICY\LENGTH', 'Address Label number is too long.');

    define('Curator\Classes\Language\Policy\ADDRESS\LINE_1\MISSING', 'Address Line 1 is required.');
    define('Curator\Classes\Language\Policy\ADDRESS\LINE_1\POLICY\INVALID', 'Address Line 1 does not meet requirements. Only numbers & characters are allowed.');
    define('Curator\Classes\Language\Policy\ADDRESS\LINE_1\POLICY\LENGTH', 'Address Line 1 is too long.');
?>
