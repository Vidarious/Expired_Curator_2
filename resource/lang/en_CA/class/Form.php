<?php
/*
 * en_CA language file for Curator Form class. Contains all user visable messaging.
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator\Classes\Language\Form;

    //Deny direct access to file.
    if(!defined('Curator\Config\APPLICATION'))
    {
        header("Location: " . "http://" . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
        die();
    }

    define('Curator\Classes\Language\Form\MESSAGE\ERROR_GENERAL', 'Your form submission could not be processed, please try again.');
    define('Curator\Classes\Language\Form\MESSAGE\ERROR_FIELD', 'Your form submission could not be processed, please fix the flagged fields and resubmit.');

    define('Curator\Classes\Language\Form\HAZARD_VALIDATE_TOKEN', 'A submitted form failed validation due to bad tokens.');

    define('Curator\Classes\Language\Form\HAZARD_VALIDATE_INVISIBLE_CAPTCHA', 'A submitted form failed validation due to bad invisible CAPTCHA.');

    define('Curator\Classes\Language\Form\HAZARD_VALIDATE_FORM_TYPE', 'A submitted form failed validation due to bad form type.');

    define('Curator\Classes\Language\Form\HAZARD_VALIDATE_WHITELIST_FIELD', '$_POST whitelist verification failed. The following field was not found in the posted data: ');
    define('Curator\Classes\Language\Form\HAZARD_VALIDATE_WHITELIST_COUNT', '$_POST whitelist verification failed. The number of fields in the account whitelist does not match the amount of fields in $_POST.');

    define('Curator\Classes\Language\Form\MESSAGE\FLOOD', 'You must wait before you can submit this form again.');

    define('Curator\Classes\Language\Form\MESSAGE\RECAPTCHA', 'ReCAPTCHA has not been verified. Please try again.');
?>