<?php
/*
 * fr_CA language file for Curator database class. Contains all user visable messaging.
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

    define('Curator\Classes\Language\Database\ERROR_CONNECT', 'Impossible de se connecter à la base de données SQL. PDO ERREUR: ');

    define('Curator\Classes\Language\Database\ERROR_PREPARE', 'Impossible de préparer la requête de base de données. L\'instruction Prepare renvoyait la valeur FALSE. Déclaration: ');

    define('Curator\Classes\Language\Database\ERROR_BIND', 'Incapable de se lier à des valeurs requête de base de données. ');

    define('Curator\Classes\Language\Database\ERROR_EXECUTE', 'Impossible d\'exécuter la requête préparée . Exécuter retourné FALSE.');
?>