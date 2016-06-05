<?php
 /*
 * Class for page account management. Singleton design. There can only be one instance of this class.
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

    class Account
    {
        //Class Objects
        public $Create = NULL;

        //Object initalization. Singleton design.
        protected function __construct()
        {
            
        }

        //Returns the singleton instance of the account class. Singleton design.
        public static function initialize()
        {
            static $instance = NULL;

            if($instance === NULL)
            {
                $instance = new static();
            }

            return $instance;
        }

        //Singleton design.
        private function __clone() {}

        //Singleton design.
        private function __wakeup() {}

        //Initializes the account creation class.
        public function initializeCreate()
        {
            //Create a 'Create' object to handle account creation processes.
            $this->Create = new Account\Create();
        }
    }
?>