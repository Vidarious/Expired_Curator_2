<?php
 /*
 * Curator trait file for Whitelist related traits.
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator\Traits;

    //Deny direct access to file.
    if(!defined('Curator\Config\APPLICATION'))
    {
        header("Location: " . "http://" . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
        die();
    }

    use \Curator\Config\ACCOUNT\FIELD  as FIELD;

    trait Whitelist
    {
        //Generate and return an array of enabled account field names.
        public function getWhitelist_CreateAccount($formType, $invisibleCAPTCHA)
        {
            //Email and password are absolute requirements (minimum).
            $fields = array('Email', 'Email_Confirm', 'Password', 'Password_Confirm', $invisibleCAPTCHA, 'Form_Type', 'cToken');

            //If Google ReCAPTCHA is enabled.
            if(\Curator\Config\FORM\RECAPTCHA)
            {
                array_push($fields, 'g-recaptcha-response');
            }
            if(FIELD\USERNAME)
            {
                array_push($fields, 'Username');
            }
            if(FIELD\GIVEN_NAME)
            {
                array_push($fields, 'Given_Name');
            }
            if(FIELD\FAMILY_NAME)
            {
                array_push($fields, 'Family_Name');
            }
            if(FIELD\PREFERRED_NAME)
            {
                array_push($fields, 'Preferred_Name');
            }
            if(FIELD\TITLE)
            {
                array_push($fields, 'Title');
            }
            if(FIELD\GENDER)
            {
                array_push($fields, 'Gender');
            }
            if(FIELD\DATE_OF_BIRTH)
            {
                array_push($fields, 'Date_Of_Birth');
            }
            if(FIELD\PHONE)
            {
                array_push($fields, 'Phone');
            }
            if(FIELD\ADDRESS)
            {
                array_push($fields, 'Address_Label');
                array_push($fields, 'Address_Line_1');
                array_push($fields, 'Address_Line_2');
                array_push($fields, 'Address_Line_3');
                array_push($fields, 'Address_City');
                array_push($fields, 'Address_Province');
                array_push($fields, 'Address_Postal');
                array_push($fields, 'Address_Country');
            }

            return($fields);
        }
    }
 ?>