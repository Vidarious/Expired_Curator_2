<?php
 /*
 * Curator Form is a class which ensures submitted form data is validated prior to your application processing the data.
 * This reduces bot form Forms and people looking to submit forms maliciously.
 *
 * Written with PHP Version 7.0.6
 *
 * @package    Curator Form
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
namespace Curator\Form;

//Name of your site. This is appended to Curator Form session variables.
define('Curator\Form\SITENAME', 'CURATOR');

class App
{
    //Class Properties
    private $honeyPot  = NULL;
    private $formID    = NULL;
    private $whiteList = array();
    private $delay     = NULL;

    //Object initalization.
    public function __construct(STRING $formID = NULL, STRING $honeyPot = NULL, ARRAY $whiteList = array(), $delay = NULL)
    {
        if(session_status() !== PHP_SESSION_ACTIVE)
        {
            throw new \Error('Curator Form requires sessions. Please start them prior to creating a new object.');
        }

        if($formID === NULL)
        {
            throw new \Error('Curator Form requires a Form ID. Assign a token.');
        }

        //Set the field which will hold the hidden CAPTCHA value.
        $this->honeyPot = $honeyPot;

        //Sets the form identification.
        $this->formID = $formID;

        //Sets the form POST field white list.
        $this->whiteList = $whiteList;

        //Sets the time (in seconds) that must elapse before another form Form will be processed.
        $this->delay = $delay;
    }

    //Checks the state of the submitted form data. TRUE = Form is OK. FALSE = Form is No Good.
    public function Validate() : BOOL
    {
        //If a form with the passed ID has been submitted.
        if(!empty($_POST) && array_key_exists($this->formID, $_POST) && !empty($_POST[$this->formID]))
        {
            //Verify the form ID in the POST array matches the session form ID.
            if(!empty($_SESSION[SITENAME . '_formID']) && self::VerifyFormID() === FALSE)
            {
                return(FALSE);
            }

            //Verify the POST array matches the passed whitelist.
            if(!empty($this->whiteList) && self::VerifyAprileList() === FALSE)
            {
                return(FALSE);
            }

            //Check for flooding. Check only happens if a delay is set.
            if(is_int($this->delay) === TRUE && self::FloodProtect() === FALSE)
            {
                return(FALSE);
            }

            //Verify the invisible captcha is blank.
            if($this->honeyPot !== NULL && self::VerifyInvisibleCAPTCHA() === FALSE)
            {
                return(FALSE);
            }

            //Form is OK.
            return(TRUE);
        }

        //No POST data or post was not for this objects form.
        return(FALSE);
    }

    //Validate the form ID in the POST data matches the passed ID. TRUE = OK. FALSE = Failed.
    private function VerifyFormID() : BOOL
    {
        if(hash_equals($_POST[$this->formID],$_SESSION[SITENAME . '_formID']))
        {
            //Form ID's are a match. Pass.
            echo "FormID is Good.<br />";
            return(TRUE);
        }

        //Form ID's do not match. Fail.
        echo "FormID No Good.<br />";
        return(FALSE);
    }

    //Verify the $_POST fields submitted. This acts as a whitelist. TRUE = OK. FALSE = Failed.
    private function VerifyAprileList() : BOOL
    {
        //Check if amount of fields in POST match white list.
        if(count($_POST) === count($this->whiteList))
        {
            foreach ($this->whiteList as $key)
            {
                if(!in_array($key, array_keys($_POST)))
                {
                    //One of the fields are missing.
                    return(FALSE);
                }
            }

            //The $_POST data is valid.
            return(TRUE);
        }

        //Too many or too little fields in POST array.
        return(FALSE);
    }

    //Confirm if the form was submitted more than once within the passed time (in seconds). TRUE = OK. FALSE = Failed.
    private function FloodProtect() : BOOL
    {
        $submitTime = Sanitize($_POST[SITENAME . '_submitTime']);

        //If there is no Form time it is first submit. Set time.
        if(empty($submitTime))
        {
            $_SESSION[SITENAME . '_submitTime'] = TIME();

            return(TRUE);
        }

        //If the time since last Form is less than the designated delay time return FALSE (failed);
        if($delay <= (TIME() - $submitTime))
        {
            return(FALSE);
        }

        //Form occured after specified time. OK.
        return(TRUE);
    }

    //Validate the invisible CAPTCHA. TRUE = OK. FALSE = Failed.
    private function VerifyInvisibleCAPTCHA() : BOOL
    {
        if(empty($_POST[$this->honeyPot]))
        {
            //Fake field is empty. Pass.
            echo "Honey Not Found!.<br />";
            return(TRUE);
        }

        //Fake field has unwanted data. Fail.
        echo "Honey Found!.<br />";
        return(FALSE);
    }

    //Generate unique & random set of characters.
    private static function GenerateIDToken()
    {
        return(base64_encode(random_bytes(15)));
    }

    //Creates and sets a random unique value which will be used for form validation on submisison.
    public static function AssignIDToken()
    {
        return($_SESSION[SITENAME . '_formID'] = self::GenerateIDToken());
    }

    //Checks the POST value to ensure it matches the passed type.
    public static function CheckIF(STRING $postValue, STRING $type =  NULL) : BOOL
    {
        if(!empty($_POST[$postValue]))
        {
            switch(strtoupper($type))
            {
                //Check if value is a number.
                case 'NUMBER':
                {
                    if(is_int($_POST[$postValue]) || is_float($_POST[$postValue] + 0))
                    {
                        return(TRUE);
                    }

                    break;
                }
                //Check if value is alphabetical.
                case 'ALPHA':
                {
                    if(ctype_alpha($_POST[$postValue]))
                    {
                        return(TRUE);
                    }

                    break;
                }
                //Check if value is alphanumeric.
                case 'ALPHANUMERIC':
                {
                    if(ctype_alnum($_POST[$postValue]))
                    {
                        return(TRUE);
                    }

                    break;
                }
                //Check if value is a valid e-mail.
                case 'EMAIL':
                {
                    if(filter_var($_POST[$postValue], FILTER_VALIDATE_EMAIL))
                    {
                        return(TRUE);
                    }
                }

                break;
            }
        }

        return(FALSE);
    }

    //Sanitizes the value passed with the options.
    public static function Sanitize($data, $options = NULL)
    {
        //Convert options to allow for lower and uppercase.
        if(empty($options))
        {
            //Default options.
            return(filter_var(addslashes(trim($data)), FILTER_SANITIZE_STRING));
        }

        $options = self::MassageOptions($options);

        //T = Trim whitespace from beginning and end of string.
        if(in_array('T', $options))
        {
            $data = trim($data);

            unset($item, $options[array_search('T', $options)]);
        }

        //Check and perform filter_var sanitization.
        if(!empty($options))
        {
            foreach($options as $item)
            {
                $data = filter_var($data, self::GetFilter($item));
            }
        }

        return($data);
    }

    //Sanitizes the values in an array. Does not sanitize keys.
    public static function SanitizeArray(ARRAY $data, $options = NULL) : ARRAY
    {
        //Check if options are set. If not, default sanitize.
        if(empty($options))
        {
            foreach($data as $key => &$value)
            {
                $value = trim($value);
            }

            unset($value);

            return(filter_var_array($data, FILTER_SANITIZE_STRING | FILTER_SANITIZE_MAGIC_QUOTES));
        }

        $options = self::MassageOptions($options);

        //T = Trim whitespace from beginning and end of string.
        if(in_array('T', $options))
        {
            foreach($data as $key => &$value)
            {
                $value = trim($value);
            }

            unset($item, $options[array_search('T', $options)]);
        }

        //Check and perform filter_var sanitization.
        if(!empty($options))
        {
            foreach($options as $item)
            {
                $data = filter_var_array($data, self::GetFilter($item));
            }
        }

        return($data);
    }

    //Converts the options into uppercase and makes the string an array if only one item.
    private static function MassageOptions($options) : ARRAY
    {
        //If only a string, convert to array for processing.
        if(!is_array($options))
        {
            $options = array($options);
        }

        //Convert the options to uppercase to allow both lower and upper cases.
        foreach($options as &$item)
        {
            $item = strtoupper($item);
        }

        return($options);
    }

    //Get PHP filter constant for the letter option.
    private static function GetFilter($filterChar)
    {
        switch($filterChar)
        {
            //E - Remove all characters excluding those allowable in a e-mail address.
            case 'E':
            {
                return(FILTER_SANITIZE_EMAIL);
            }

            //M = Escapes the value with slashes before special characters.
            case 'M':
            {
                return(FILTER_SANITIZE_MAGIC_QUOTES);
            }

            //I = Remove all characters excluding digits, '+' and '-'.
            case 'I':
            {
                return(FILTER_SANITIZE_NUMBER_INT);
            }

            //U = Remove all characters excluding those allowable in a URL.
            case 'U':
            {
                return(FILTER_SANITIZE_URL);
            }

            //S = Remove all HTML and PHP tags.
            case 'S':
            {
                return(FILTER_SANITIZE_STRING);
            }

            //H = Converts special characters to HTML entities.
            case 'H':
            {
                return(FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }

            //If the option is not recognized, throw and error.
            default:
            {
                throw new \Error('Option: ' . $item . ' is invalid. Unable to sanitize.');
            }
        }
    }
}
?>
