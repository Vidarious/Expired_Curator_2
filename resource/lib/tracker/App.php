<?php
 /*
 * Curator Tracker is a class which tracks users page navigation.
 *
 * Written with PHP Version 7.0.6
 *
 * @package    Curator Tracker
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
namespace Curator\Tracker;

//Path to your site's home page or default page.
define('Curator\Tracker\HOME', '\index.php');

//Name of your site. This is appended to Curator Trackers session variables.
define('Curator\Tracker\SITENAME', 'CURATOR');

class App
{
    //Class Properties
    public $pageCurrent  = HOME;
    public $pagePrevious = HOME;

    //Object initalization. Singleton design.
    protected function __construct()
    {
        self::GetCurrentPage();
        self::GetPastPage();
        self::UpdateSessionPages();
    }

    //Singleton design.
    private function __clone() {}
    private function __wakeup() {}

    //Returns the singleton instance of the Session object.
    public static function GetTracker()
    {
        static $trackerInstance = NULL;

        if($trackerInstance === NULL)
        {
        $trackerInstance = new static();
        }

        return($trackerInstance);
    }

    //Get current page URI.
    private function GetCurrentPage()
    {
        if(!empty($_SERVER['REQUEST_URI']))
        {
            $this->pageCurrent = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        }
    }

    //Get past page URI.
    private function GetPastPage()
    {
        if(!empty($_SESSION[SITENAME . '_PageCurrent']))
        {
            $pastCurrentPage = filter_var($_SESSION[SITENAME . '_PageCurrent'], FILTER_SANITIZE_URL);
        }

        //Set previous page if it was found in session.
        if(!empty($pastCurrentPage))
        {
            $this->pagePrevious = $pastCurrentPage;
        }
    }

    //Update the Current and Past page session values.
    private function UpdateSessionPages()
    {
        $_SESSION[SITENAME . '_PageCurrent']  = $this->pageCurrent;
        $_SESSION[SITENAME . '_PagePrevious'] = $this->pagePrevious;
    }
}
?>
