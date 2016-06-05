<?php
 /*
 * Class for account form processing.
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator\Classes;

    //Deny direct access to file.
    if(!defined('Curator\Config\APPLICATION'))
    {
        header("Location: " . "http://" . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
        die();
    }

    use \Curator\Config\FORM           as FORM;
    use \Curator\Config\PATH           as PATH;
    use \Curator\Classes\Language\Form as LANG;
    use \Curator\Traits                as TRAITS;

    //Include the Form language error messaging file.
    require_once(PATH\ROOT . 'resource/locale/' . \Curator\Config\LANG\CURATOR_APPLICATION . '/class/Form.php');

    //Load Whitelist trait.
    require_once(PATH\ROOT . 'resource/traits/Whitelist.php');

    class Form
    {
        //Class Variables
        public  $formMessagesError   = array();
        public  $formMessagesSuccess = array();
        private $formType            = NULL;
        private $invisibleCAPTCHA    = NULL;
        public  $whitelist           = NULL;

        //Class Objects
        private $Session             = NULL;

        //Object initalization.
        public function __construct($formType)
        {
            $this->formType = $formType;

            self::setInvisibleCAPTCHA();

            //Initialize session object.
            $this->Session  = Session::getSession();
        }

        //Sets the invisible CAPTCHA POST field based on form type.
        private function setInvisibleCAPTCHA()
        {
            if($this->formType == 'Create_Account')
            {
                $this->invisibleCAPTCHA = 'username';
            }
        }

        //Generates the POST URI for the account creation form.
        public function getActionURI()
        {
            $requestScheme = 'http://';

            if(isset($_SERVER['HTTPS']))
            {
                $requestScheme = 'https://';
            }

            return($requestScheme . filter_var($_SERVER['SERVER_NAME'], FILTER_SANITIZE_URL) . filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL));
        }

        //Sanitizes POST data.
        public function sanitizePOST($value)
        {
            if(!empty($_POST[$value]))
            {
                $_POST[$value] = filter_var($_POST[$value], FILTER_SANITIZE_MAGIC_QUOTES);

                return;
            }

            $_POST[$value] = NULL;
        }

        //Set secure form token.
        public function assignToken()
        {
            return($this->Session->setValue('Curator_formToken', self::generateToken()));
        }

        //Generate unique form token
        private function generateToken()
        {
            return(md5(uniqid(microtime(), true)));
        }

        //Check the validity of the posted form.
        public function validate()
        {
            if(FORM\RECAPTCHA && self::verifyReCAPTCHA() === FALSE)
            {
                return FALSE;
            }

            if(self::verifyFormType() === FALSE)
            {
                return FALSE;
            }

            if(self::verifyInvisibleCAPTCHA() === FALSE)
            {
                return FALSE;
            }

            if(self::verifyToken() === FALSE)
            {
                return FALSE;
            }

            if(self::verifyWhitelist() === FALSE)
            {
                return FALSE;
            }

            return TRUE;
        }

        //Validate the form type in the POST data matches the passed value.
        private function verifyFormType()
        {
            $this->sanitizePOST('Form_Type');

            if($_POST['Form_Type'] == $this->formType)
            {
                return TRUE;
            }

            //Form is not verified.
            $logMessage = new \Curator\Application\Log(__CLASS__, __METHOD__);
            $logMessage ->saveHazard(LANG\HAZARD_VALIDATE_FORM_TYPE . ' Requested Form Type: "' . $this->formType . '" - Received POST Form Type: "' . $_POST['Form_Type'] . '"');

            return FALSE;
        }

        //Validate Google ReCAPTCHA
        private function verifyReCAPTCHA()
        {
            $reCaptcha = new ReCaptcha(FORM\RECAPTCHA\SECRET);

            if (!empty($_POST["g-recaptcha-response"]))
            {
                $response = $reCaptcha->verifyResponse($this->Session->userIP, $_POST['g-recaptcha-response']);

                if($response != NULL && $response->success)
                {
                    return TRUE;
                }
            }
            array_push($this->formMessagesError, LANG\MESSAGE\RECAPTCHA);

            return FALSE;
        }

        //Validate the form invisible CAPTCHA
        private function verifyInvisibleCAPTCHA()
        {
            self::sanitizePOST($this->invisibleCAPTCHA);

            if(empty($_POST[$this->invisibleCAPTCHA]))
            {
                return TRUE;
            }

            //Form is not verified. Save error to log.
            $logMessage = new \Curator\Application\Log(__CLASS__, __METHOD__);
            $logMessage ->saveHazard(LANG\HAZARD_VALIDATE_INVISIBLE_CAPTCHA . ' invisibleCAPTCHA: "' . $_POST[$this->invisibleCAPTCHA] . '"');

            return FALSE;
        }

        //Validate the form token.
        private function verifyToken()
        {
            self::sanitizePOST('cToken');

            if($this->Session->getValue('Curator_formToken') == $_POST['cToken'])
            {
                return TRUE;
            }

            //Form is not verified. Save error to log.
            $logMessage = new \Curator\Application\Log(__CLASS__, __METHOD__);
            $logMessage ->saveHazard(LANG\HAZARD_VALIDATE_TOKEN . ' sessionToken: "' . $this->Session->getValue('Curator_formToken') . '" - POST[cToken]: "' . $_POST['cToken'] . '"');

            return FALSE;
        }

        //Verify the $_POST fields submitted.
        private function verifyWhitelist()
        {
            $this->whitelist = TRAITS\Whitelist::getWhitelist_CreateAccount($this->formType, $this->invisibleCAPTCHA);

            if(sizeof($_POST) === sizeof($this->whitelist))
            {
                foreach ($this->whitelist as $key)
                {
                    if(!in_array($key, array_keys($_POST)))
                    {
                        //One of the fields are missing. Fail. Save error to log.
                        $logMessage = new \Curator\Application\Log(__CLASS__, __METHOD__);
                        $logMessage ->saveHazard(LANG\HAZARD_VALIDATE_WHITELIST_FIELD . $key);

                        return FALSE;
                    }
                }

                //The $_POST data is valid. Pass.
                return TRUE;
            }

            //The $_POST and $whitelist sizes are different. Fail. Save error to log.
            $logMessage = new \Curator\Application\Log(__CLASS__, __METHOD__);
            $logMessage ->saveHazard(LANG\HAZARD_VALIDATE_WHITELIST_COUNT);

            return FALSE;
        }

        //Confirm if the POSTED form has been submitted within the passed value.
        public function checkFormFlood($delay)
        {
            if($this->Session->getValue('Curator_formFlood') && (time() - $this->Session->getValue('Curator_formFlood') <= $delay))
            {
                return TRUE;
            }

            $this->Session->setValue('Curator_formFlood');
            return FALSE;
        }

        //Set session variable for flood form flood protection.
        public function setFormFlood()
        {
            $this->Session->setValue('Curator_formFlood', TIME());
        }
    }
?>