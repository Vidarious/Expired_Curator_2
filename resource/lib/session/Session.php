<?php
 /*
 * Class for session control. Singleton design. There can only be one instance of this class.
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

    use \Curator\Config  as CONFIG;
    use \Curator\Traits  as TRAITS;
    use \Curator\Classes as CLASSES;

    class Session
    {
        //Class Variables
        private $userIP = NULL;

        //Class Objects
        public $Cookie  = NULL;

        public $test = array(); //FOR TESTING

        //Object initalization. Singleton design.
        protected function __construct()
        {
            //Initialize cookie object.
            $this->Cookie = Cookie::getCookie();

            //Setup the session configuration details.
            self::setupSession();

            //Start the session.
            session_start();

            //Secure the session.
            self::secureSession();
        }

        //Singleton design.
        private function __clone() {}

        //Singleton design.
        private function __wakeup() {}

        //Returns the singleton instance of the session class. Singleton design.
        public static function getSession()
        {
            static $sessionInstance = NULL;

            if($sessionInstance === NULL)
            {
                $sessionInstance = new static();
            }

            return $sessionInstance;
        }

        //Set all session configuration settings.
        protected function setupSession()
        {
            //Set session settings.
            ini_set('session.use_cookies',             1);
            ini_set('session.use_only_cookies',        1);
            ini_set('session.cookie_lifetime',         0);
            ini_set('session.cookie_httponly',         1);
            ini_set('session.use_trans_sid',           0);
            ini_set('session.use_strict_mode',         1);
            ini_set('session.entropy_length',          32);
            ini_set('session.hash_bits_per_character', 5);
            ini_set('session.hash_function',           CONFIG\SESSION\ENCRYPTION);

            session_name(CONFIG\SESSION\NAME);
        }

        //Secures the session from hijacking.
        protected function secureSession()
        {
            //Determines users IP.
            self::setIP();

            if(!isset($_SESSION['Curator_Status']) || !isset($_SESSION['Curator_Lang']) || !self::confirmTimeOut() || !self::confirmUser() || !self::confirmIP())
            {
                //Secure check(s) failed. Create new session.
                self::newSession();
            }
            else
            {
                //Session is secure. See if the Session ID needs to be regenerated.
                self::tryRegenerate();
            }
        }

        //Encode the passed value with the Curator's salt.
        public function encode($value)
        {
            return(hash(CONFIG\SESSION\ENCRYPTION, $value . CONFIG\SESSION\IDENTIFIER));
        }

        //Verify the passed value is a valid IP address.
        public function validateIP($ip)
        {
            if((!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === FALSE) || (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === FALSE))
            {
                return $ip;
            }

            return 'N/A';
        }

        //Determine and validate the users IP. This function checks (in the best order) to obtain the users IP.
        protected function setIP()
        {
            if(!empty($_SERVER['HTTP_CLIENT_IP']))
            {
                $this->userIP = self::validateIP(htmlspecialchars($_SERVER['HTTP_CLIENT_IP']));
            }
            else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $ipString = explode(',', htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR']));
                $ip       = trim($ipString[count($ipString) - 1]);

                $this->userIP = self::validateIP($ip);
            }
            else
            {
                $this->userIP = self::validateIP(htmlspecialchars($_SERVER['REMOTE_ADDR']));
            }

            return($this->userIP);
        }

        //Confirms if users IP is same as the sessions user.
        protected function confirmIP()
        {
            //Check if IP Enforcement is enabled.
            if(CONFIG\SESSION\ENFORCE_IP === TRUE)
            {
                $userKey = self::encode($this->userIP);

                if(!isset($_SESSION['Curator_userKey']) || ($_SESSION['Curator_userKey'] != $userKey))
                {
                    $this->test[] = "User IP check failed ...";
                    return FALSE;
                }
            }

            return TRUE;
        }

        //Confirms if users agent is same as the sessions user.
        protected function confirmUser()
        {
            if(CONFIG\SESSION\ENFORCE_USERAGENT === TRUE)
            {
                $userAgent = self::encode(htmlspecialchars($_SERVER['HTTP_USER_AGENT']));

                if(!isset($_SESSION['Curator_userAgent']) || ($_SESSION['Curator_userAgent'] != $userAgent))
                {
                    $this->test[] = "User agent check failed ...";
                    return FALSE;
                }
            }

            return TRUE;
        }

        //Session timeout management.
        protected function confirmTimeOut()
        {
            if(isset($_SESSION['Curator_idleTime']))
            {
                $idleLength = time() - $_SESSION['Curator_idleTime'];

                if($idleLength < CONFIG\SESSION\TIMEOUT)
                {
                    //***
                    $_SESSION['Curator_idleTime2'] = $_SESSION['Curator_idleTime']; //TESTING ONLY. DELETE LATER.
                    //***
                    $_SESSION['Curator_idleTime'] = time();
                    return TRUE;
                }
            }
            $this->test[] = "Timeout failed .. Last idle time: " . date("i:s", time() - $_SESSION['Curator_idleTime']);

            return FALSE;
        }

        //Initialize new session data.
        protected function newSession()
        {
            //Destroy cookie, session and setup new cookie & session.
            $this->Cookie->destroyCookies();
            $this->Cookie->setupCookies();

            self::destroySession();
            self::setupSession();

            session_start();

            if(CONFIG\SESSION\ENFORCE_IP === TRUE)
            {
                $_SESSION['Curator_userKey'] = self::encode($this->userIP);
            }

            if(CONFIG\SESSION\ENFORCE_USERAGENT === TRUE)
            {
                $_SESSION['Curator_userAgent'] = self::encode(htmlspecialchars($_SERVER['HTTP_USER_AGENT']));
            }

            $_SESSION['Curator_startTime'] = $_SESSION['Curator_idleTime'] = $_SESSION['Curator_regenTime'] = time();
            $_SESSION['Curator_Status']    = TRUE;
            $_SESSION['Curator_Lang']      = CONFIG\LANG\CURATOR_USER_DEFAULT;

            $this->test[] = "New session started ...";
            $_SESSION['MESSAGE'] = $this->test;
        }

        //Destroy old session.
        protected function destroySession()
        {
            session_unset();
            session_destroy();
            session_write_close();

            $_SESSION = array();
        }

        //Two trys to regenerate Session ID for added security. Both configurable by admin
        protected function tryRegenerate()
        {
            if(!self::regenerateTime())
            {
                self::regeneratePercent();
            }
        }

        //Regenerate Session ID based on admin set time length. Regenerates every XXX seconds.
        protected function regenerateTime()
        {
            if(CONFIG\SESSION\REGENERATE\TIME\ENFORCE === TRUE)
            {
                //Last time the session ID was regenerated.
                $regenLength = time() - $_SESSION['Curator_regenTime'];

                //Check if the last regenerated time has exceeded the admin setting.
                if($regenLength > CONFIG\SESSION\REGENERATE\TIME)
                {
                    session_regenerate_id(TRUE);
                    $_SESSION['Curator_regenTime'] = time();
                    $_SESSION['MESSAGE'][0] = "\nSession time exceeded .. Generated new ID ...";
                    return TRUE;
                }
            }
        }

        //Regenerate Session ID every X% of the time which is admin set.
        protected function regeneratePercent()
        {
            if(CONFIG\SESSION\REGENERATE\PERCENT\ENFORCE === TRUE)
            {
                //Generate based on % chance set by admin (Value: 1 - 100) out of 100.
                if(($test = mt_rand(0,100)) <= CONFIG\SESSION\REGENERATE\PERCENT)
                {
                    session_regenerate_id(TRUE);
                    $_SESSION['MESSAGE'][0] = "5% hit! Regenerated new ID ...";
                }
                //****
                $_SESSION['RandomTEST'] = $test; //TESTING ONLY
                //****
            }
        }

        //Return a Session class variable.
        public function __get($property = NULL)
        {
            if(property_exists($this, $property))
            {
                return $this->$property;
            }
        }

        //Returns the requested session value.
        public static function getValue($variable = NULL)
        {
            if(isset($_SESSION[$variable]))
            {
                return $_SESSION[$variable];
            }

            return NULL;
        }

        //Sets the requested session value.
        public static function setValue($variable = NULL, $value = NULL)
        {
            if(isset($variable))
            {
                if(empty($value))
                {
                    unset($_SESSION[$variable]);
                }
                else
                {
                    return($_SESSION[$variable] = $value);
                }
            }
        }
    }
?>