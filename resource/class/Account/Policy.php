<?php
/*
 * Class for account rule enforcement.
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator\Classes\Account;

    //Deny direct access to file.
    if(!defined('Curator\Config\APPLICATION'))
    {
        header("Location: " . "http://" . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
        die();
    }

    use \Curator\Config\PATH                                  as PATH;
    use \Curator\Classes\Language\Policy                      as LANG;
    use \Curator\Classes\Language\Policy\PASSWORD\POLICY\SHOW as SHOW;
    use \Curator\Config\ACCOUNT\FIELD\SETTING                 as RULE;
    use \Curator\Config\ACCOUNT\FIELD                         as FIELD;
    use \Curator\Traits                                       as TRAITS;

    //Include the Field language error messaging file.
    require_once(PATH\ROOT . 'resource/locale/' . \Curator\Config\LANG\CURATOR_APPLICATION . '/class/Policy.php');

    //Include password trait file.
    require_once(PATH\ROOT . 'resource/traits/Password.php');

    class Policy
    {
        //Class Variables
        private $SQL              = NULL;
        private $Form             = NULL;
        public  $error            = FALSE;
        public  $Email            = array();
        public  $Email_Confirm    = array();
        public  $Password         = array();
        public  $Password_Confirm = array();
        public  $Username         = array();
        public  $Given_Name       = array();
        public  $Family_Name      = array();
        public  $Preferred_Name   = array();
        public  $Title            = array();
        public  $Gender           = array();
        public  $Date_Of_Birth    = array();
        public  $Phone            = array();
        public  $Address_Label    = array();
        public  $Address_Line_1   = array();
        public  $Address_Line_2   = array();
        public  $Address_Line_3   = array();
        public  $Address_City     = array();
        public  $Address_Province = array();
        public  $Address_Postal   = array();
        public  $Address_Country  = array();

        //Object initalization. Call parent constructor to gain access to class methods and variables/objects
        public function __construct($Form)
        {
            //TEST VARIABLES
            $_POST['Email'] = 'jdruhan.home@gmail.com';
            $_POST['Email_Confirm'] = 'jdruhan.home@gmail.com';
            $_POST['Password'] = 'aA1!tes3pass3wordt';
            $_POST['Password_Confirm'] = 'aA1!tes3pass3wordt';
            $_POST['Username'] = 'Vidarious';

            $this->Form = $Form;
            $this->SQL  = new \Curator\Classes\Database\SQL();
        }

        //Check all form fields to ensure the user data falls within the rules.
        //REQUIRES: $Form->whitelist.
        public function checkRules()
        {
            if(in_array('Email', $this->Form->whitelist))
            {
                self::checkEmail();
            }

            if(in_array('Email_Confirm', $this->Form->whitelist))
            {
                self::checkEmailConfirm();
            }

            if(in_array('Password', $this->Form->whitelist))
            {
                self::checkPassword();
            }

            if(in_array('Password_Confirm', $this->Form->whitelist))
            {
                self::checkPasswordConfirm();
            }

            if(in_array('Username', $this->Form->whitelist))
            {
                self::checkUsername();
            }

            if(in_array('Given_Name', $this->Form->whitelist))
            {
                self::checkGivenName();
            }

            if(in_array('Family_Name', $this->Form->whitelist))
            {
                self::checkFamilyName();
            }

            if(in_array('Preferred_Name', $this->Form->whitelist))
            {
                self::checkPreferredName();
            }

            if(in_array('Title', $this->Form->whitelist))
            {
                self::checkTitle();
            }

            if(in_array('Gender', $this->Form->whitelist))
            {
                self::checkGender();
            }

            if(in_array('Date_Of_Birth', $this->Form->whitelist))
            {
                self::checkDateOfBirth();
            }

            if(in_array('Phone', $this->Form->whitelist))
            {
                self::checkPhone();
            }

            if(in_array('Address_Label', $this->Form->whitelist))
            {
                self::checkAddressLabel();
            }

            if(in_array('Address_Line_1', $this->Form->whitelist))
            {
                self::checkAddressLine1();
            }

            if(in_array('Address_Line_2', $this->Form->whitelist))
            {
                self::checkAddressLine2();
            }

            if(in_array('Address_Line_3', $this->Form->whitelist))
            {
                self::checkAddressLine3();
            }

            if(in_array('Address_City', $this->Form->whitelist))
            {
                self::checkAddressCity();
            }

            if(in_array('Address_Province', $this->Form->whitelist))
            {
                self::checkAddressProvince();
            }

            if(in_array('Address_Postal', $this->Form->whitelist))
            {
                self::checkAddressPostal();
            }

            if(in_array('Address_Country', $this->Form->whitelist))
            {
                self::checkAddressCountry();
            }

            //If any error occures, clear the password fields.
            if($this->error)
            {
                $this->Password['Value']         = NULL;
                $this->Password_Confirm['Value'] = NULL;
            }

            return $this->error;
        }

        //Issue an error to the form.
        public function addMessage(&$field, $error)
        {
            $field['Message'] = $error;
            $this->error      = TRUE;
        }

        //Check email field against Curator fields.
        public function checkEmail()
        {
            $this->Email['Value']   = NULL;
            $this->Email['Message'] = NULL;

            //Confirm email value exists.
            if(empty($_POST['Email']))
            {
                self::addMessage($this->Email, LANG\EMAIL\MISSING);

                return FALSE;
            }

            //Confirm e-mail is a valid format.
            if(filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL) === FALSE)
            {
                self::addMessage($this->Email, LANG\EMAIL\INVALID);

                return FALSE;
            }

            $this->Email['Value'] = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
        }

        //Check email confirm field against Curator fields.
        public function checkEmailConfirm()
        {
            $this->Email_Confirm['Value']   = NULL;
            $this->Email_Confirm['Message'] = NULL;

            if($this->Email['Message'] === NULL)
            {
                //Confirm e-mail and e-mail confirm fields match.
                if($this->Email['Value'] !== filter_var($_POST['Email_Confirm'], FILTER_SANITIZE_EMAIL))
                {
                    self::addMessage($this->Email_Confirm, LANG\EMAIL_CONFIRM\MISMATCH);

                    return FALSE;
                }

                $this->Email_Confirm['Value'] = filter_var($_POST['Email_Confirm'], FILTER_SANITIZE_EMAIL);
            }
        }

        //Check password field against Curator fields.
        public function checkPassword()
        {
            $this->Password['Value']   = NULL;
            $this->Password['Message'] = NULL;

            //Confirm value exists.
            if(empty($_POST['Password']))
            {
                self::addMessage($this->Password, LANG\PASSWORD\MISSING);

                return FALSE;
            }

            //Set min and max password length rule.
            if(!self::passwordExpression('/^(?=.{' . RULE\PASSWORD\MIN_LENGTH . ',' . RULE\PASSWORD\MAX_LENGTH . '}$).+$/', SHOW\LENGTH))
            {
                return FALSE;
            }

            //Set lower character requirement rule.
            if(RULE\PASSWORD\LOWER_CHAR)
            {
                if(!self::passwordExpression('/^(.*?[a-z]){' . RULE\PASSWORD\LOWER_CHAR . ',}.*$/', SHOW\LOWER_CHAR))
                {
                    return FALSE;
                }
            }

            //Set upper character requirement rule.
            if(RULE\PASSWORD\UPPER_CHAR)
            {
                if(!self::passwordExpression('/^(.*?[A-Z]){' . RULE\PASSWORD\UPPER_CHAR . ',}.*$/', SHOW\UPPER_CHAR))
                {
                    return FALSE;
                }
            }

            //Set number character requirement rule.
            if(RULE\PASSWORD\NUMBER)
            {
                if(!self::passwordExpression('/^(.*?\d){' . RULE\PASSWORD\NUMBER . ',}.*$/', SHOW\NUMBER_CHAR))
                {
                    return FALSE;
                }
            }

            //Set special character requirement rule.
            if(RULE\PASSWORD\SPECIAL_CHAR)
            {
                if(!self::passwordExpression('/^(.*?[^\w]|.*?[_]){' . RULE\PASSWORD\SPECIAL_CHAR . ',}.*$/', SHOW\SPECIAL_CHAR))
                {
                    return FALSE;
                }
            }

            //If word restriction is enabled, validate password against words.
            if(RULE\PASSWORD\WORD)
            {
                if($restricted = $this->SQL->checkRestrictedWord($_POST['Password']))
                {
                    self::addMessage($this->Password, LANG\PASSWORD\POLICY);

                    if(RULE\PASSWORD\DISPLAY)
                    {
                        array_push($this->Form->formMessagesError, SHOW\GENERAL . '<li>' . SHOW\RESTRICTED_WORD . '<b>' . $restricted['word'] . '</b></li>');
                    }

                    return FALSE;
                }
            }

            $this->Password['Value'] = TRAITS\Password::encrypt($_POST['Password_Confirm']);
        }

        //Validate the password expression.
        public function passwordExpression($policy, $error)
        {
            if(filter_var($_POST['Password'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => $policy))) === FALSE)
            {
                self::addMessage($this->Password, LANG\PASSWORD\POLICY);

                if(RULE\PASSWORD\DISPLAY)
                {
                    array_push($this->Form->formMessagesError, SHOW\GENERAL . '<li>' . $error . '</li>');
                }

                return FALSE;
            }

            return TRUE;
        }

        //Check password confirm field against Curator fields.
        public function checkPasswordConfirm()
        {
            $this->Password_Confirm['Value']   = NULL;
            $this->Password_Confirm['Message'] = NULL;

            if($this->Password['Message'] === NULL)
            {
                //Confirm password and password confirm fields match.
                if($_POST['Password_Confirm'] === $_POST['Password'])
                {
                    //Secure the password
                    $this->Password['Value'] = $this->Password_Confirm['Value'] = TRAITS\Password::encrypt($_POST['Password_Confirm']);

                    return TRUE;
                }

                self::addMessage($this->Password_Confirm, LANG\PASSWORD_CONFIRM\MISMATCH);
            }

            return FALSE;
        }

        //Check username field against Curator fields.
        public function checkUsername()
        {
            $this->Username['Value']   = NULL;
            $this->Username['Message'] = NULL;

            if(FIELD\USERNAME\REQUIRED)
            {
                //Confirm value exists.
                if(empty($_POST['Username']))
                {
                    self::addMessage($this->Username, LANG\USERNAME\MISSING);

                    return FALSE;
                }
            }

            if(!empty($_POST['Username']))
            {
                $policy = '/^(?=.{' . RULE\USERNAME\MIN_LENGTH . ',' . RULE\USERNAME\MAX_LENGTH . '}$).+$/';

                //Confirm username falls between min and max length.
                if(filter_var($_POST['Username'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => $policy))) === FALSE)
                {
                    self::addMessage($this->Username, LANG\USERNAME\POLICY\LENGTH);

                    return FALSE;
                }

                $policy = '/^[a-zA-Z0-9]*$/';

                //Confirm username matches policy (letters and numbers only).
                if(filter_var($_POST['Username'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => $policy))) === FALSE)
                {
                    self::addMessage($this->Username, LANG\USERNAME\POLICY\INVALID);

                    return FALSE;
                }
            }

            $this->Username['Value'] = filter_var($_POST['Username'], FILTER_SANITIZE_STRING);
        }

        //Check given name field against Curator fields.
        public function checkGivenName()
        {
            $this->Given_Name['Value']   = NULL;
            $this->Given_Name['Message'] = NULL;

            if(FIELD\GIVEN_NAME\REQUIRED)
            {
                //Confirm value exists.
                if(empty($_POST['Given_Name']))
                {
                    self::addMessage($this->Given_Name, LANG\GIVEN_NAME\MISSING);

                    return FALSE;
                }
            }

            if(!empty($_POST['Given_Name']))
            {
                //Check length
                if(strlen($_POST['Given_Name']) > 100) //100 is DB field length.
                {
                    self::addMessage($this->Given_Name, LANG\GIVEN_NAME\POLICY\LENGTH);

                    return FALSE;
                }

                //Restrict special characters.
                $policy = '/^[^0-9!@#$%^&*()_{}|:";\[\]<>,\?]*$/';

                //Confirm name does not contain numbers.
                if(filter_var($_POST['Given_Name'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => $policy))) === FALSE)
                {
                    self::addMessage($this->Given_Name, LANG\GIVEN_NAME\POLICY\INVALID);

                    return FALSE;
                }
            }

            $this->Given_Name['Value'] = trim(filter_var($_POST['Given_Name'], FILTER_SANITIZE_STRING));
        }

        //Check family name field against Curator fields.
        public function checkFamilyName()
        {
            $this->Family_Name['Value']   = NULL;
            $this->Family_Name['Message'] = NULL;

            if(FIELD\FAMILY_NAME\REQUIRED)
            {
                //Confirm value exists.
                if(empty($_POST['Family_Name']))
                {
                    self::addMessage($this->Family_Name, LANG\FAMILY_NAME\MISSING);

                    return FALSE;
                }
            }

            if(!empty($_POST['Family_Name']))
            {
                //Check length
                if(strlen($_POST['Family_Name']) > 100) //100 is DB field length.
                {
                    self::addMessage($this->Family_Name, LANG\FAMILY_NAME\POLICY\LENGTH);

                    return FALSE;
                }

                //Restrict special characters.
                $policy = '/^[^0-9!@#$%^&*()_{}|:";\[\]<>,\?]*$/';

                //Confirm name does not contain numbers.
                if(filter_var($_POST['Family_Name'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => $policy))) === FALSE)
                {
                    self::addMessage($this->Family_Name, LANG\FAMILY_NAME\POLICY\INVALID);

                    return FALSE;
                }
            }

            $this->Family_Name['Value'] = filter_var($_POST['Family_Name'], FILTER_SANITIZE_STRING);
        }

        //Check preferred name field against Curator fields.
        public function checkPreferredName()
        {
            $this->Preferred_Name['Value']   = NULL;
            $this->Preferred_Name['Message'] = NULL;

            if(FIELD\PREFERRED_NAME\REQUIRED)
            {
                //Confirm value exists.
                if(empty($_POST['Preferred_Name']))
                {
                    self::addMessage($this->Preferred_Name, LANG\PREFERRED_NAME\MISSING);

                    return FALSE;
                }
            }

            if(!empty($_POST['Preferred_Name']))
            {
                //Check length
                if(strlen($_POST['Preferred_Name']) > 100) //100 is DB field length.
                {
                    self::addMessage($this->Preferred_Name, LANG\PREFERRED_NAME\POLICY\LENGTH);

                    return FALSE;
                }

                //Restrict special characters.
                $policy = '/^[^0-9!@#$%^&*()_{}|:";\[\]<>,\?]*$/';

                //Confirm name does not contain numbers.
                if(filter_var($_POST['Preferred_Name'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => $policy))) === FALSE)
                {
                    self::addMessage($this->Preferred_Name, LANG\PREFERRED_NAME\POLICY\INVALID);

                    return FALSE;
                }
            }

            $this->Preferred_Name['Value'] = filter_var($_POST['Preferred_Name'], FILTER_SANITIZE_STRING);
        }

        //Check title field against Curator fields.
        public function checkTitle()
        {
            $this->Title['Value']   = NULL;
            $this->Title['Message'] = NULL;

            if(FIELD\TITLE\REQUIRED)
            {
                //Confirm value exists.
                if(empty($_POST['Title']))
                {
                    self::addMessage($this->Title, LANG\TITLE\MISSING);

                    return FALSE;
                }
            }

            if(!empty($_POST['Title']))
            {
                //Check length
                if(strlen($_POST['Title']) > 20) //20 is DB field length.
                {
                    self::addMessage($this->Title, LANG\TITLE\POLICY\LENGTH);

                    return FALSE;
                }

                //Restrict special characters.
                $policy = '/^[^0-9!@#$%^&*()_{}|:";\[\]<>,\?]*$/';

                //Confirm name does not contain numbers.
                if(filter_var($_POST['Title'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => $policy))) === FALSE)
                {
                    self::addMessage($this->Title, LANG\TITLE\POLICY\INVALID);

                    return FALSE;
                }
            }

            $this->Title['Value'] = filter_var($_POST['Title'], FILTER_SANITIZE_STRING);
        }

        //Check gender field against Curator fields.
        public function checkGender()
        {
            $this->Gender['Value']   = NULL;
            $this->Gender['Message'] = NULL;

            if(FIELD\GENDER\REQUIRED)
            {
                //Confirm value exists.
                if(empty($_POST['Gender']))
                {
                    self::addMessage($this->Gender, LANG\GENDER\MISSING);

                    return FALSE;
                }
            }

            if(!empty($_POST['Gender']))
            {
                //Check length
                if(strlen($_POST['Gender']) > 1) //1 is DB field length.
                {
                    self::addMessage($this->Gender, LANG\GENDER\POLICY\LENGTH);

                    return FALSE;
                }

                //Restrict to letters.
                $policy = '/^[a-zA-Z]*$/';

                //Confirm name does not contain numbers.
                if(filter_var($_POST['Gender'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => $policy))) === FALSE)
                {
                    self::addMessage($this->Gender, LANG\GENDER\POLICY\INVALID);

                    return FALSE;
                }
            }

            $this->Gender['Value'] = filter_var($_POST['Gender'], FILTER_SANITIZE_STRING);
        }

        //Check date of birth field against Curator fields.
        public function checkDateOfBirth()
        {
            $this->Date_Of_Birth['Value']   = NULL;
            $this->Date_Of_Birth['Message'] = NULL;

            if(FIELD\DATE_OF_BIRTH\REQUIRED)
            {
                //Confirm value exists.
                if(empty($_POST['Date_Of_Birth']))
                {
                    self::addMessage($this->Date_Of_Birth, LANG\DATE_OF_BIRTH\MISSING);

                    return FALSE;
                }
            }

            if(!empty($_POST['Date_Of_Birth']))
            {
                //Restrict to date format MM/DD/YYYY.
                $policy = '/^(\d{2}\/\d{2}\/\d{4})$/';

                //Confirm name does not contain numbers.
                if(filter_var($_POST['Date_Of_Birth'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => $policy))) === FALSE)
                {
                    self::addMessage($this->Date_Of_Birth, LANG\DATE_OF_BIRTH\POLICY\INVALID);

                    return FALSE;
                }

                //Validate date
                if(strtotime($_POST['Date_Of_Birth']) === FALSE)
                {
                  self::addMessage($this->Date_Of_Birth, LANG\DATE_OF_BIRTH\POLICY\BAD_DATE);

                  return FALSE;
                }
            }

            $this->Date_Of_Birth['Value'] = filter_var($_POST['Date_Of_Birth'], FILTER_SANITIZE_STRING);
        }

        //Check phone field against Curator fields.
        public function checkPhone()
        {
            $this->Phone['Value']   = NULL;
            $this->Phone['Message'] = NULL;

            if(FIELD\PHONE\REQUIRED)
            {
                //Confirm value exists.
                if(empty($_POST['Phone']))
                {
                    self::addMessage($this->Phone, LANG\PHONE\MISSING);

                    return FALSE;
                }
            }

            if(!empty($_POST['Phone']))
            {
              //Restrict to phone number to numbers.
              $policy = '/^[0-9]*$/';

              //Confirm phone only has numbers
              if(filter_var($_POST['Phone'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => $policy))) === FALSE)
              {
                  self::addMessage($this->Phone, LANG\PHONE\POLICY\INVALID);

                  return FALSE;
              }

                //Check length
                if(strlen($_POST['Phone']) > 50) //50 is DB field length.
                {
                    self::addMessage($this->Phone, LANG\PHONE\POLICY\LENGTH);

                    return FALSE;
                }
            }

            $this->Phone['Value'] = filter_var($_POST['Phone'], FILTER_SANITIZE_STRING);
        }

        //Check address label field against Curator fields.
        public function checkAddressLabel()
        {
            $this->Address_Label['Value']   = NULL;
            $this->Address_Label['Message'] = NULL;

            if(!empty($_POST['Address_Label']))
            {
              //Restrict to letters and numbers.
              $policy = '/^[a-zA-Z0-9]*$/';

              //Confirm address lebel to letters and numbers.
              if(filter_var($_POST['Address_Label'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => $policy))) === FALSE)
              {
                  self::addMessage($this->Address_Label, LANG\ADDRESS_LABEL\POLICY\INVALID);

                  return FALSE;
              }

                //Check length
                if(strlen($_POST['Address_Label']) > 50) //50 is DB field length.
                {
                    self::addMessage($this->Address_Label, LANG\ADDRESS_LABEL\POLICY\LENGTH);

                    return FALSE;
                }
            }

            $this->Address_Label['Value'] = filter_var($_POST['Address_Label'], FILTER_SANITIZE_STRING);
        }

        //Check address line 1 field against Curator fields.
        public function checkAddressLine1()
        {
            $this->Address_Line_1['Value']   = NULL;
            $this->Address_Line_1['Message'] = NULL;

            //Letters, numbers, periods, hyphens only.
            //Check length
            //Required check

            if(FIELD\ADDRESS\LINE_1\REQUIRED)
            {
                //Confirm value exists.
                if(empty($_POST['Address_Line_1']))
                {
                    self::addMessage($this->Address_Line_1, LANG\ADDRESS\LINE_1\MISSING);

                    return FALSE;
                }
            }

            if(!empty($_POST['Address_Line_1']))
            {
              //Restrict to phone number to numbers.
              $policy = '/^[a-zA-Z0-9]*$/';

              //Confirm phone only has numbers
              if(filter_var($_POST['Address_Line_1'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => $policy))) === FALSE)
              {
                  self::addMessage($this->Address_Line_1, LANG\ADDRESS\LINE_1\POLICY\INVALID);

                  return FALSE;
              }

              //Check length
              if(strlen($_POST['Address_Line_1']) > 20) //100 is DB field length.
              {
                  self::addMessage($this->Address_Line_1, LANG\ADDRESS\LINE_1\POLICY\LENGTH);

                  return FALSE;
                }
            }

            $this->Address_Line_1['Value'] = filter_var($_POST['Address_Line_1'], FILTER_SANITIZE_STRING);
        }

        //Check address line 2 field against Curator fields.
        public function checkAddressLine2()
        {

            //Letters, numbers, periods, hyphens only.
            //Check length

            $this->Address_Line_2['Value']   = NULL;
            $this->Address_Line_2['Message'] = NULL;

            $this->Address_Line_2['Value'] = filter_var($_POST['Address_Line_2'], FILTER_SANITIZE_STRING);
        }

        //Check address line 3 field against Curator fields.
        public function checkAddressLine3()
        {
            $this->Address_Line_3['Value']   = NULL;
            $this->Address_Line_3['Message'] = NULL;

            $this->Address_Line_3['Value'] = filter_var($_POST['Address_Line_3'], FILTER_SANITIZE_STRING);
        }

        //Check address city field against Curator fields.
        public function checkAddressCity()
        {
            $this->Address_City['Value']   = NULL;
            $this->Address_City['Message'] = NULL;

            //Letters, numbers, periods, hyphens only.
            //Check length

            $this->Address_City['Value'] = filter_var($_POST['Address_City'], FILTER_SANITIZE_STRING);
        }

        //Check address province field against Curator fields.
        public function checkAddressProvince()
        {
            $this->Address_Province['Value']   = NULL;
            $this->Address_Province['Message'] = NULL;

            //Letters, numbers, periods, hyphens only.
            //Check length
            //Check required

            $this->Address_Province['Value'] = filter_var($_POST['Address_Province'], FILTER_SANITIZE_STRING);
        }

        //Check address postal field against Curator fields.
        public function checkAddressPostal()
        {
            $this->Address_Postal['Value']   = NULL;
            $this->Address_Postal['Message'] = NULL;

            //Letters, numbers, periods, hyphens only.
            //Check length
            //Check required

            $this->Address_Postal['Value'] = filter_var($_POST['Address_Postal'], FILTER_SANITIZE_STRING);
        }

        //Check address country field against Curator fields.
        public function checkAddressCountry()
        {
            $this->Address_Country['Value']   = NULL;
            $this->Address_Country['Message'] = NULL;

            //Letters, numbers, periods, hyphens only.
            //Check length
            //Check required

            $this->Address_Country['Value'] = filter_var($_POST['Address_Country'], FILTER_SANITIZE_STRING);
        }
    }
?>
