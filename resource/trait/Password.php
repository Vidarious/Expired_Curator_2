<?php
 /*
 * Curator trait file for Password related traits.
 *
 * PHP Version 7.0.2
 *
 * @package    Curator
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
    namespace Curator\Traits;

    //Deny direct access to file.
    if(!defined('Curator\Config\APPLICATION'))
    {
        header("Location: " . "http://" . filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
        die();
    }

    use \Curator\Config\ACCOUNT\FIELD\SETTING\PASSWORD as PASSWORD;

    trait Password
    {
        public function encrypt($value)
        {
            if(PASSWORD\ENCRYPTION == 'PASSWORD_BCRYPT')
            {
                $options = ['cost' => PASSWORD\ENCRYPTION\BCRYPT\COST ];

                return(password_hash($value, PASSWORD_BCRYPT, $options));
            }
        }

        public function check($toCheck, $toCheckAgainst)
        {
            if(PASSWORD\ENCRYPTION == 'PASSWORD_BCRYPT')
            {
                return(password_verify($toCheck, $toCheckAgainst));
            }
        }
    }