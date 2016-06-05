<?php
/*
 * en_CA language file for Curator Log class. Contains all user visable messaging.
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator\Classes\Language\Log;

    //Deny direct access to file.
    if(!defined('Curator\Config\APPLICATION'))
    {
        header("Location: " . "http://" . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
        die();
    }

    define('Curator\Classes\Language\Log\ERROR_HEADER', 'Request cannot be processed ...');
    define('Curator\Classes\Language\Log\ERROR_BODY', 'Sorry but your request cannot be processed at this time. Please try again.');
    define('Curator\Classes\Language\Log\ERROR_FOOTER', 'If you continue to see this message please contact the administrator: ' . \Curator\Config\APPLICATION\ADMIN_EMAIL);

    define('Curator\Classes\Language\Log\HEAD_DATE', 'DATE');

    define('Curator\Classes\Language\Log\HEAD_ADDRESS', 'ADDRESS');

    define('Curator\Classes\Language\Log\HEAD_URI', 'URI');

    define('Curator\Classes\Language\Log\HEAD_CLASS', 'CLASS');

    define('Curator\Classes\Language\Log\HEAD_METHOD', 'METHOD');

    define('Curator\Classes\Language\Log\HEAD_MESSAGE', 'MESSAGE');
?>