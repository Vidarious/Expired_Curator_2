<?php
/*
 * fr_CA language file for Curator Log class. Contains all user visable messaging.
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

    define('Curator\Classes\Language\Log\ERROR_HEADER', 'Demande ne peut être traitée ...');
    define('Curator\Classes\Language\Log\ERROR_BODY', 'Désolé viser votre demande ne peut être traitée à ce moment . Veuillez réessayer.');
    define('Curator\Classes\Language\Log\ERROR_FOOTER', 'Si vous continuez à voir ce message s\'il vous plaît contacter l\'administrateur: ' . \Curator\Config\APPLICATION\ADMIN_EMAIL);

    define('Curator\Classes\Language\Log\HEAD_DATE', 'DATE');

    define('Curator\Classes\Language\Log\HEAD_ADDRESS', 'L\'ADRESS');

    define('Curator\Classes\Language\Log\HEAD_URI', 'URI');

    define('Curator\Classes\Language\Log\HEAD_CLASS', 'CLASSE');

    define('Curator\Classes\Language\Log\HEAD_METHOD', 'MÉTHODE');

    define('Curator\Classes\Language\Log\HEAD_MESSAGE', 'MESSAGE');
?>