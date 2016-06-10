<?php
 /*
 * Curator Session is a class which manages user sessions.
 *
 * Written with PHP Version 7.0.6
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
namespace Curator\Session;

class App
{
    //Class Properties (Variable)
    private $userIP = 'NULL';

    //Class initalization. Singleton design.
    protected function __construct()
    {
        //Initialize cookie settings.
        self::InitializeCookie();

        //Initialize session settings.
        self::InitializeSession();

        //Start the session.
        session_start();

        //Secure the session.
        self::SecureSession();
    }

    //Singleton design.
    private function __clone() {}
    private function __wakeup() {}

    //Returns the singleton instance of the Session object.
    public static function GetSession()
    {
        static $sessionInstance = NULL;

        if($sessionInstance === NULL)
        {
            $sessionInstance = new static();
        }

        return $sessionInstance;
    }

    //Initialize the cookie details.
    protected function InitializeCookie()
    {
        session_set_cookie_params(COOKIE_LIFETIME, COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTPONLY);
    }

    //Set all session configuration settings.
    protected function InitializeSession()
    {
        ini_set('session.use_cookies',             SESSION_USE_COOKIES);
        ini_set('session.use_only_cookies',        SESSION_USE_ONLY_COOKIES);
        ini_set('session.cookie_lifetime',         SESSION_COOKIE_LIFETIME);
        ini_set('session.cookie_httponly',         SESSION_COOKIE_HTTPONLY);
        ini_set('session.use_trans_sid',           SESSION_USE_TRANS_SID);
        ini_set('session.use_strict_mode',         SESSION_USE_STRICT_MODE);
        ini_set('session.entropy_file',            SESSION_ENTROPY_FILE);
        ini_set('session.entropy_length',          SESSION_ENTROPY_LENGTH);
        ini_set('session.hash_bits_per_character', SESSION_HASH_BITS_PER_CHARACTER);
        ini_set('session.hash_function',           SESSION_HASH_FUNCTION);

        session_name(SESSION_NAME);
    }

    //Secures the session from hijacking.
    protected function SecureSession()
    {
        //Determines users IP.
        if(IP_VALIDATION)
        {
            self::ValidateIP();
        }

        //Four confirmations to see if the session is secure.
        if(!isset($_SESSION[SESSION_NAME]) || !self::ConfirmTimeOut() || !self::ConfirmUser() || !self::ConfirmIP())
        {
            //Secure check(s) failed. Create new session.
            self::newSession();

            return();
        }

        //Session is secure. See if the Session ID needs to be regenerated.
        self::tryRegenerate();
    }

    //Validate the IP passed is a valid IPV4 or IPV6 IP address.
    public function ValidateIP()
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

                    if (filter_var($userIP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== FALSE){
                        $this->userIP = $userIP;

                        return();
                    }
                }
            }
        }

        //User IP could not be obtained through header information. 'NULL' will be used.
        return();
    }

    //Session timeout management.
    protected function ConfirmTimeOut()
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

    //Confirms if users agent is same as the sessions user.
    protected function ConfirmUser()
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

    //Confirms if users IP is same as the sessions user.
    protected function ConfirmIP()
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

    //Encode the passed value with the Curator's salt.
    public function encode($value)
    {
    return(hash(CONFIG\SESSION\ENCRYPTION, $value . CONFIG\SESSION\IDENTIFIER));
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

    //Four way session destroy.
    public function DestroySession()
    {
        session_unset();
        session_destroy();
        session_write_close();

        $_SESSION = array();
    }

    //Removes all site cookies.
    public function DestroyCookies()
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
