<?php
/*
 * Curator\Database configuration file.
 *
 * Naming Practices
 * ----------------
 * Classes    -> lower_case
 * Methods    -> PascalCase
 * Properties -> camelCase
 * Constants  -> UPPER_CASE
 *
 * PHP Version 7.0.6
 *
 * @package    Curator\Database
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
namespace Curator\Database;

//PDO database driver to use.
define('Curator\Database\DRIVER', 'MySQL');

//Address of the database server.
define('Curator\Database\HOST', 'localhost');

//Name of the database to use.
define('Curator\Database\DATABASE_NAME', 'Curator');

//Login username for database server.
define('Curator\Database\USERNAME', 'temp');

//Login password for database server.
define('Curator\Database\PASSWORD', 'temp');
?>
