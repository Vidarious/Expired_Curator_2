<?php
 /*
 * Class for page The Curator. Singleton design. There can only be one instance of this class.
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

    class Curator
    {
        //Class Objects
        public $Session  = NULL;
        public $Tracker  = NULL;
        public $Account  = NULL;

        //Object initalization. Singleton design.
        protected function __construct()
        {
            //Initialize session object.
            $this->Session  = Session::getSession();

            //Start page tracking utility.
            $this->Tracker  = Tracker::getTracker();
        }

        //Returns the singleton instance of The Curator class. Singleton design.
        public static function Initialize()
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

        //Initializes all processes required for account creation.
        public function Initialize_Account_Creation()
        {
            //Create Account object and initialize.
            $this->Account = Account::initialize();

            //Create the account creation object within account.
            $this->Account->initializeCreate();
        }
    }
?>