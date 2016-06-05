<?php
/*
 * Class for cookie manipulation. Singleton design. There can only be one instance of this class.
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

    use \Curator\Config as CONFIG;

    class Cookie
    {
        //Class Variables
        private $secureServer = NULL;

        //Object initalization. Singleton design.
        protected function __construct()
        {
            //Determine if server is running HTTPS.
            $this->secureServer = isset($_SERVER['HTTPS']);

            self::setupCookies();
        }

        //Singleton design.
        private function __clone() {}

        //Singleton design.
        private function __wakeup() {}

        //Returns the singleton instance of the cookie class. Singleton design.
        public static function getCookie()
        {
            static $cookieInstance = NULL;

            if($cookieInstance === NULL)
            {
                $cookieInstance = new static();
            }

            return $cookieInstance;
        }

        //Initialize cookie settings.
        public function setupCookies()
        {
            session_set_cookie_params(0, CONFIG\COOKIE\PATH, CONFIG\COOKIE\DOMAIN, $this->secureServer, TRUE);
        }

        //Removes all cookies.
        public function destroyCookies()
        {
            //Loop through each cookie and remove.
            foreach ($_COOKIE as $key => $value)
            {
                unset($_COOKIE[$key]);
                setcookie($key, '', time() - 3600, CONFIG\COOKIE\PATH, CONFIG\COOKIE\DOMAIN, $this->secureServer, TRUE);
            }
        }

        //Creates cookie.
        public function set($name = NULL, $value = NULL, $expire = 0)
        {
            if(isset($name) && isset($value))
            {
                return(setcookie($name, $value, time() + $expire, CONFIG\COOKIE\PATH, CONFIG\COOKIE\DOMAIN, $this->secureServer, TRUE));
            }

            return FALSE;
        }

        //Gets and returns cookie value.
        public static function get($name = NULL)
        {
            if(isset($name))
            {
                return($_COOKIE[$name]);
            }

            return FALSE;
        }

        //Deletes a cookie value.
        public static function delete($name = NULL)
        {
            if(isset($name))
            {
                unset($_COOKIE[$name]);
                return(setcookie($name, '', time() - 3600, CONFIG\COOKIE\PATH, CONFIG\COOKIE\DOMAIN, $this->secureServer, TRUE));
            }

            return FALSE;
        }
    }
?>