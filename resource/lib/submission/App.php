<?php
 /*
 * Curator Submission is a class which ensures submitted form data is validated prior to your application processing the data.
 * This reduces bot form submissions and people looking to submit forms maliciously.
 *
 * Written with PHP Version 7.0.6
 *
 * @package    Curator Submission
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
namespace Curator\Submission;

//Name of your site. This is appended to Curator Submission session variables.
define('Curator\Form\SITENAME', 'CURATOR');

class Form
{
    //Class Properties
    private $fakeField           = NULL;
    private $formID              = NULL;
    private $whiteList           = array();
    private $delay               = NULL;

    //Object initalization.
    public function __construct($formID = NULL, $fakeField = NULL, $whiteList = array(), $delay = NULL)
    {
        //Set the field which will hold the hidden CAPTCHA value.
        $this->fakeField = $fakeField;

        //Sets the form identification.
        $this->formID = $formID;

        //Sets the form POST field white list.
        $this->whiteList = $whiteList;

        //Sets the time (in seconds) that must elapse before another form submission will be processed.
        $this->$delay = $delay;
    }

    //Checks the state of the submitted form data. TRUE = Form is OK. FALSE = Form is No Good.
    public function Validate()
    {
        //If a form with the passed ID has been submitted.
        if(!empty($_POST) && array_key_exists($formID, $_POST))
        {
            //Verify the POST array matches the passed whitelist.
            if(!empty($whiteList) && self::VerifyAprileList() === FALSE)
            {
                return(FALSE);
            }

            //Check for flooding.
            if($delay !== NULL && self::FloodProtect() === FALSE)
            {
                return(FALSE);
            }

            //Verify the form ID in the POST array matches the session form ID.
            if($formID !== NULL && self::VerifyFormID() === FALSE)
            {
                return(FALSE);
            }

            //Verify the invisible captcha is blank.
            if($fakeField !== FALSE && self::VerifyInvisibleCAPTCHA() === FALSE)
            {
                return(FALSE);
            }

            //Form is OK.
            return(TRUE);
        }

        //No POST data or post was not for this objects form.
        return(FALSE);
    }

    //Verify the $_POST fields submitted. This acts as a whitelist. TRUE = OK. FALSE = Failed.
    private function VerifyAprileList()
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
    public function FloodProtect()
    {
        $submitTime = GetPOST(SITENAME . '_submitTime');

        //If there is no submission time it is first submit. Set time.
        if(empty($submitTime))
        {
            $_SESSION[SITENAME . '_submitTime'] = TIME();

            return(TRUE);
        }

        //If the time since last submission is less than the designated delay time return TRUE (failed);
        if($delay <= (TIME() - $submitTime))
        {
            return(FALSE);
        }

        //Submission occured after specified time. OK.
        return(TRUE);
    }

    //Validate the form ID in the POST data matches the passed ID. TRUE = OK. FALSE = Failed.
    private function VerifyFormID()
    {
        if(GetPOST($this->formID) == $_SESSION[SITENAME . '_formID'])
        {
            //Form ID's are a match. Pass.
            return(TRUE);
        }

        //Form ID's do not match. Fail.
        return(FALSE);
    }

    //Validate the invisible CAPTCHA. TRUE = OK. FALSE = Failed.
    private function VerifyInvisibleCAPTCHA()
    {
        if(empty(GetPOST($this->fakeField)))
        {
            //Fake field is empty. Pass.
            return(TRUE);
        }

        //Fake field has unwanted data. Fail.
        return(FALSE);
    }

    //Creates and sets a random unique value which will be used for form validation on submisison.
    public static function AssignIDToken()
    {
        return($_SESSION[SITENAME . '_formID'] = self::GenerateToken());
    }

    //Generate unique & random set of characters.
    public static function GenerateIDToken()
    {
        return(md5(uniqid(microtime(), true)));
    }

    //Returns a sanitized POST value.
    public function GetPOST($value)
    {
        if(!empty($_POST[$value]))
        {
            $_POST[$value] = filter_var($_POST[$value], FILTER_SANITIZE_MAGIC_QUOTES);

            return($_POST[$value])
        }

        return($_POST[$value] = NULL);
    }
}
?>
