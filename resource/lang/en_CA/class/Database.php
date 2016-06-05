<?php
/*
 * en_CA language file for Curator database class. Contains all user visable messaging.
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator\Classes\Language\Database;

    //Deny direct access to file.
    if(!defined('Curator\Config\APPLICATION'))
    {
        header("Location: " . "http://" . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
        die();
    }

    define('Curator\Classes\Language\Database\ERROR_CONNECT', 'Unable to connect to the SQL database. PDO Error: ');

    define('Curator\Classes\Language\Database\ERROR_PREPARE', 'Unable to prepare database query. Prepare statement returned FALSE. Statement: ');

    define('Curator\Classes\Language\Database\ERROR_BIND', 'Unable to bind values to database query. ');

    define('Curator\Classes\Language\Database\ERROR_EXECUTE', 'Unable to execute prepared query. Execute returned FALSE.');
?>