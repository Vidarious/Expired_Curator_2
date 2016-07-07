<?php
 /*
 * Curator Session is a class which manages user sessions and cookies.
 *
 * Naming Convention
 * -----------------
 * Classes    -> PascalCase
 * Methods    -> PascalCase
 * Properties -> camelCase
 * Constants  -> UPPER_CASE
 *
 * Requires PHP 7+
 * Written with PHP Version 7.0.6
 *
 * @package    Curator Session
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
namespace Curator\Session;

require_once('config.php');

class App
{
    //Class Properties
    private $userIP = 'NULL';

    //Class initalization. Singleton design.
    protected function __construct()
    {
        //Initialize cookie settings.
        self::InitializeCookie();

        //Initialize & start the session.
        session_start(self::InitializeSession());

        if(isset($_SESSION[SESSION_NAME]['status']) && $_SESSION[SESSION_NAME]['status'] === TRUE)
        {
            //Secure the session.
            if(self::SecureSession() === TRUE)
            {
                //Session is secure. See if the Session ID needs to be regenerated.
                self::TryRegenerate();

                //Assigning new idle checkpoint.
                $_SESSION[SESSION_NAME]['idleTime'] = time();

                return;
            }
        }

        //Start a new session.
        self::NewSession();
    }

    //Singleton design.
    private function __clone() {}
    private function __wakeup() {}

    //Returns the singleton instance of the Session object.
    public static function GetSession() : SELF
    {
        static $sessionInstance = NULL;

        if($sessionInstance === NULL)
        {
            $sessionInstance = new static();
        }

        return($sessionInstance);
    }

    //Initialize the cookie details.
    protected function InitializeCookie()
    {
        session_set_cookie_params(COOKIE_LIFETIME, COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTPONLY);
    }

    //Set all session configuration settings.
    protected function InitializeSession() : ARRAY
    {
        $sessionSettings = array
        (
            'use_cookies'             => SESSION_USE_COOKIES,
            'use_only_cookies'        => SESSION_USE_ONLY_COOKIES,
            'cookie_lifetime'         => SESSION_COOKIE_LIFETIME,
            'cookie_httponly'         => SESSION_COOKIE_HTTPONLY,
            'use_trans_sid'           => SESSION_USE_TRANS_SID,
            'use_strict_mode'         => SESSION_USE_STRICT_MODE,
            'entropy_file'            => SESSION_ENTROPY_FILE,
            'entropy_length'          => SESSION_ENTROPY_LENGTH,
            'hash_bits_per_character' => SESSION_HASH_BITS_PER_CHARACTER,
            'hash_function'           => SESSION_HASH_FUNCTION,
            'name'                    => SESSION_NAME
        );

        return($sessionSettings);
    }

    //Secures the session from hijacking.
    protected function SecureSession() : BOOL
    {
        //Four confirmations to see if the session is secure.
        if(!isset($_SESSION[SESSION_NAME]['status']) || !self::ConfirmTimeOut())
        {
            //Secure check(s) failed.
            return(FALSE);
        }

        //Checks user agent if enabled.
        if(SESSION_USERAGENT_CHECK && isset($_SERVER['HTTP_USER_AGENT']) && self::ConfirmUserAgent() === FALSE)
        {
            return(FALSE);
        }

        //Determines users IP and validates it.
        if(IP_VALIDATION && self::DetermineIP() === TRUE && self::ConfirmIP() === FALSE)
        {
            return(FALSE);
        }

        return(TRUE);
    }

    //Verifies if the current session has timed out.
    protected function ConfirmTimeOut() : BOOL
    {
        //Check if an existing session is in progress.
        if(isset($_SESSION[SESSION_NAME]['idleTime']))
        {
            $idleLength = time() - $_SESSION[SESSION_NAME]['idleTime'];

            if($idleLength < SESSION_IDLE_TIME)
            {
                return(TRUE);
            }
        }

        //Session session has timed out or this is a new session.
        return (FALSE);
    }

    //Confirms if users agent is same as the sessions user.
    //The user agent is hashed for added protection.
    protected function ConfirmUserAgent() : BOOL
    {
        if(isset($_SESSION[SESSION_NAME]['userAgent']))
        {
            if(password_verify(htmlspecialchars($_SERVER['HTTP_USER_AGENT']), $_SESSION[SESSION_NAME]['userAgent']) === TRUE)
            {
                //Pass if recorded user agent is good.
                return(TRUE);
            }
        }

        //Fail if there is no recorded user agent or if the user agent recorded does not match the current.
        return(FALSE);
    }

    //Determine the user IP from $_SERVER data is a valid IPV4 or IPV6 IP address.
    protected function DetermineIP() : BOOL
    {
        //Create array of possible IP locations.
        $ipLocations = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');

        //Check each possible location for valid IP.
        foreach($ipLocations as $key)
        {
            if(array_key_exists($key, $_SERVER) === TRUE)
            {
                $ipData = htmlspecialchars($_SERVER[$key]);

                foreach(explode(',', $ipData) as $userIP)
                {
                    $userIP = trim($userIP);

                    if (filter_var($userIP, FILTER_VALIDATE_IP) !== FALSE)
                    {
                        $this->userIP = $userIP;

                        return(TRUE);
                    }
                }
            }
        }

        //User IP could not be obtained through header information. 'NULL' will be used.
        return(FALSE);
    }

    //Confirms if users IP is same as the sessions user.
    protected function ConfirmIP() : BOOL
    {
        //Check if IP Enforcement is enabled. The IP is hashed and hidden in a non-obvious variable.
        if(password_verify($this->userIP, $_SESSION[SESSION_NAME]['userKey']) === FALSE)
        {
            return(FALSE);
        }

        //Pass if user IP is same as the recorded session IP or if the user does not have a valid IP. Keeps the applcation running.
        return(TRUE);
    }

    //Hash the passed value with the Curator's salt.
    public function Hash($value) : STRING
    {
        return(password_hash($value, PASSWORD_DEFAULT));
    }

    //Starts a new session for the user.
    public function NewSession()
    {
        self::DestroySession();

        session_start(self::InitializeSession());

        if(IP_VALIDATION && self::DetermineIP() === TRUE)
        {
            $_SESSION[SESSION_NAME]['userKey'] = self::Hash($this->userIP);
        }

        if(SESSION_USERAGENT_CHECK === TRUE && isset($_SERVER['HTTP_USER_AGENT']))
        {
            $_SESSION[SESSION_NAME]['userAgent'] = self::Hash(htmlspecialchars($_SERVER['HTTP_USER_AGENT']));
        }

        //Set all the necessary session timings.
        $_SESSION[SESSION_NAME]['startTime'] = $_SESSION[SESSION_NAME]['idleTime'] = $_SESSION[SESSION_NAME]['regenTime'] = time();
        $_SESSION[SESSION_NAME]['status']    = TRUE;
    }

    //Session ID is regenerated in two ways.
    //#1: The session ID is regenerated after a set amount of time defined by the config.
    //#2: The session ID is regenerated on a random chance.
    protected function TryRegenerate()
    {
        if(!self::RegenerateTime())
        {
            self::RegeneratePercent();
        }
    }

    //Regenerate Session ID based on config set time length.
    protected function RegenerateTime() : BOOL
    {
        if(is_int(SESSION_REGEN_TIME) === TRUE && isset($_SESSION[SESSION_NAME]['regenTime']))
        {
            //Last time the session ID was regenerated.
            $regenLength = time() - $_SESSION[SESSION_NAME]['regenTime'];

            //Check if the last regenerated time has exceeded the config setting.
            if($regenLength > SESSION_REGEN_TIME)
            {
                //Session ID needs to be regenerated.
                session_regenerate_id(TRUE);
                $_SESSION[SESSION_NAME]['regenTime'] = time();

                return(TRUE);
            }
        }

        //Regenerate session ID option disabled or session ID does not need to be regenerated.
        return(FALSE);
    }

    //Regenerate Session ID every X% of the time which is admin set.
    protected function RegeneratePercent()
    {
        if(is_int(SESSION_REGEN_CHANCE) === TRUE)
        {
            //Generate based on % chance set by config (Value: 1 - 100) out of 100.
            if(random_int(0, 100) <= SESSION_REGEN_CHANCE)
            {
                //Buy a lotto ticket! Session will be regenerated.
                session_regenerate_id(TRUE);
            }
        }
    }

    //Returns the requested session value.
    public static function GetValue($variable = NULL)
    {
        if(isset($_SESSION[$variable]))
        {
            return($_SESSION[$variable]);
        }

        return(NULL);
    }

    //Sets the requested session value.
    public static function SetValue($variable = NULL, $value = NULL)
    {
        if(isset($variable))
        {
            if(empty($value) && isset($_SESSION[$variable]))
            {
                //Delete the variable.
                unset($_SESSION[$variable]);
            }
            else
            {
                $_SESSION[$variable] = $value;
            }
        }
    }

    //Gets the cookie value.
    public static function GetCookie($name = NULL)
    {
        if(isset($name) && isset($_COOKIE[$name]))
        {
            return($_COOKIE[$name]);
        }

        return(NULL);
    }

    //Sets a cookie value.
    public static function SetCookie($name = NULL, $value = NULL, $expire = COOKIE_LIFETIME, $path = COOKIE_PATH, $domain = COOKIE_DOMAIN, $secure = COOKIE_SECURE, $httpOnly = COOKIE_HTTPONLY) : BOOL
    {
        if(isset($name) && isset($value))
        {
            setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);

            return(TRUE);
        }

        return(FALSE);
    }

    //Deletes a cookie value.
    public static function DeleteCookie($name = NULL) : BOOL
    {
        if(isset($name) && isset($_COOKIE[$name]))
        {
            unset($_COOKIE[$name]);
            setcookie($name, '', time() - 3600);

            return(TRUE);
        }

        return(FALSE);
    }

    //Four way session destroy.
    public function DestroySession()
    {
        session_unset();
        session_destroy();
        session_write_close();

        $_SESSION = array();
    }

    //Removes all site cookies.
    public function DestroyCookie()
    {
        //Loop through each cookie and remove.
        foreach ($_COOKIE as $key => $value)
        {
            //Deletes cookie.
            unset($_COOKIE[$key]);

            //Expires cookie.
            setcookie($key, '', time() - 3600);
        }
    }
}
?>
