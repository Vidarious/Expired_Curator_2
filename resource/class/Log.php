<?php
/*
 * Class for handling log requests (errors and warnings).
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator\Application;

    //Deny direct access to file.
    if(!defined('Curator\Config\APPLICATION'))
    {
        header("Location: " . "http://" . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
        die();
    }

    use \Curator\Config as CONFIG;
    use \Curator\Classes\Language\Log as LANGUAGE;

    require_once(CONFIG\PATH\ROOT . 'resource/locale/' . CONFIG\LANG\CURATOR_APPLICATION . '/class/Log.php');

class Log
{
    //Class Variables
    public $className   = NULL;
    public $methodName  = NULL;
    public $logPath     = NULL;
    public $logMessage  = NULL;

    //Object initialization. Sets the object variables for class and method.
    public function __construct($className = NULL, $methodName = NULL)
    {
        date_default_timezone_set(CONFIG\APPLICATION\TIMZEONE);

        $this->className  = $className;
        $this->methodName = $methodName;
    }

    //Sets log message and error path then sends it to be written to log file.
    public function saveError($logMessage = 'N/A')
    {
        $this->logPath    = CONFIG\PATH\LOG\ERROR;
        $this->logMessage = $logMessage;

        self::writeLog();
        self::throwError();
    }

    //Sets log message and hazard path then sends it to be written to log file.
    public function saveHazard($logMessage = 'N/A')
    {
        if(isset($_SERVER['REMOTE_ADDR']))
        {
            $logMessage .= ' - IP: ' . filter_var($_SERVER['REMOTE_ADDR'], FILTER_SANITIZE_ENCODED);
        }

        $this->logPath    = CONFIG\PATH\LOG\HAZARD;
        $this->logMessage = $logMessage;

        self::writeLog();
    }

    //Builds the log message and writes it to the correct log file.
    private function writeLog()
    {
        $messageFinal = array();

        //Build log message
        $messageFinal[] = PHP_EOL . LANGUAGE\HEAD_DATE    . ": " . date('F d, Y \a\t g:i A e', htmlspecialchars($_SERVER['REQUEST_TIME']));
        $messageFinal[] = PHP_EOL . LANGUAGE\HEAD_ADDRESS . ": " . htmlspecialchars($_SERVER['REMOTE_ADDR']);
        $messageFinal[] = PHP_EOL . LANGUAGE\HEAD_URI     . ": " . htmlspecialchars($_SERVER['REQUEST_URI']);
        $messageFinal[] = PHP_EOL . LANGUAGE\HEAD_CLASS   . ": " . $this->className;
        $messageFinal[] = PHP_EOL . LANGUAGE\HEAD_METHOD  . ": " . $this->methodName;
        $messageFinal[] = PHP_EOL . LANGUAGE\HEAD_MESSAGE . ": " . $this->logMessage;

        //Write to log.
        error_log(PHP_EOL . "**********", 3, $this->logPath);

        foreach($messageFinal as $error)
        {
            error_log($error, 3, $this->logPath);
        }

        error_log(PHP_EOL . "**********", 3, $this->logPath);
    }

    //Throw error to the user.
    private function throwError()
    {
        die('<H3>' . LANGUAGE\ERROR_HEADER . '</H3> <P>' . LANGUAGE\ERROR_BODY . '</P><P>' . LANGUAGE\ERROR_FOOTER);
    }
}
 ?>