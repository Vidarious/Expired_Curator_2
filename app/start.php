<?php
 /*
 * Primary application file for Curator. Include this file to initialize The Curator.
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator;

    use \Curator\Config\PATH as PATH;
    use \Curator\Classes     as CLASSES;

    //Load Curator configuartion data.
    require_once('config/curator.php');

    //Load debug utilities. Development only.
    require_once('lib/debug/vendor/debug.php');

    //Automatically include classes for created objects.
    function autoLoad($className)
    {
        //Extract class file name from namespace path.
        $fileName = explode('\\', $className);

        //Create path to class. Supports 1 sub-folder deep loading.
        if(sizeof($fileName) === 3)
        {
            $classLocation = PATH\CLASSES . $fileName[2] . '.php';
        }
        else
        {
            $classLocation = PATH\CLASSES . $fileName[2] . '/' . $fileName[3] . '.php';
        }

        //Verify class file exists and load.
        if(file_exists($classLocation))
        {
            require_once $classLocation;
        }
    }

    //Register auto-load function.
    spl_autoload_register('\Curator\autoLoad');

    //Initialize The Curator.
    $theCurator = CLASSES\Curator::Initialize();
?>
