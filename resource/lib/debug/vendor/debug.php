<?php
    require_once('autoload.php');
   
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
    
    //Use dump() to print pretty vars.
?>