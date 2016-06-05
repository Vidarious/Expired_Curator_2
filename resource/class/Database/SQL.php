<?php
 /*
 * Class for SQL statements.
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator\Classes\Database;

    //Deny direct access to file.
    if(!defined('Curator\Config\APPLICATION'))
    {
        header("Location: " . "http://" . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
        die();
    }

    class SQL extends \Curator\Classes\Database
    {
        public function __construct()
        {
            parent::__construct();
        }

        //Checks the passed string against the restricted word list. If match is found, return the restricted word(s).
        public function checkRestrictedWord($string)
        {
            $query = "SELECT word FROM `Restricted_Password` WHERE INSTR(LOWER(:string), LOWER(word))";

            self::prepareStatement($query);
            self::bindValue('string', $string);
            self::executeQuery();

            return self::getResultSingle();
        }
    }
?>