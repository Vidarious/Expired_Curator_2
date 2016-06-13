<?php
 /*
 * Class for page tracking. Singleton design. There can only be one instance of this class.
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator\Classes;

    use \Curator\Config as CONFIG;

    //Deny direct access to file.
    if(!defined('Curator\Config\APPLICATION'))
    {
        header("Location: " . "http://" . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
        die();
    }

    class Tracker
    {
        //Class Objects
        private $Session     = NULL;

        //Class Variables
        public $pageCurrent  = NULL;
        public $pagePrevious = CONFIG\PATH\HOMEPAGE;

        //Object initalization. Singleton design.
        protected function __construct()
        {
            //Initialize session object.
            $this->Session  = Session::getSession();

            self::getCurrentPage();
            self::getPastPage();
            self::updateSessionPages();
        }

        //Returns the singleton instance of the tracker class. Singleton design.
        public static function getTracker()
        {
            static $trackerInstance = NULL;

            if($trackerInstance === NULL)
            {
                $trackerInstance = new static();
            }

            return $trackerInstance;
        }

        //Singleton design.
        private function __clone() {}

        //Singleton design.
        private function __wakeup() {}

        //Get current page URI.
        private function getCurrentPage()
        {
            $this->pageCurrent = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        }

        //Get past page URI.
        private function getPastPage()
        {
            $pastCurrentPage = filter_var($this->Session->getValue('Curator_PageCurrent'), FILTER_SANITIZE_URL);

            if($pastCurrentPage !== NULL)
            {
                $this->pagePrevious = $pastCurrentPage;
            }
        }

        //Update the Current and Past page session values.
        private function updateSessionPages()
        {
            $this->Session->setValue('Curator_PageCurrent', $this->pageCurrent);
            $this->Session->setValue('Curator_PagePrevious', $this->pagePrevious);
        }
    }
?>