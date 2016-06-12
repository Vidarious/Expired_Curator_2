<?php
/*
 * Curator Session configuration file.
 *
 * Written with PHP Version 7.0.6
 *
 * @package    Curator Database
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
namespace Curator\Session;

//Assign the name of your site's sessions. Must contain at least 1 character.
define('Curator\Session\SESSION_NAME', 'MYSITESESSION');

//Sets user IP validation. Helps prevent session hijacking.
//IP validation is not always accurate due to many user factors.
//If IP cannot obtained user can still be session validated.
define('Curator\Session\IP_VALIDATION', TRUE);

//Session idle timeout value. Default is 1800 seconds (30 minutes.)
//This is the maximum of idle time (seconds) that can elapse between user actions.
define('Curator\Session\SESSION_IDLE_TIME', 15);

//Setting for user agent (browser) verification. Suggested setting is TRUE.
//User agent checking is not always accurate due to many user factors.
define('Curator\Session\SESSION_USERAGENT_CHECK', TRUE);

//Your site salt value for encrypting various pieces of user data.
define('Curator\Session\SESSION_SITE_SALT', 'JKfjknfjkfn389f8fhf38FHh830Fq3');

//Sets the amount of time that can elapse until the session ID is regenerated for added security.
//Suggested value is 300 seconds (5 minutes). Set to FALSE to disable this option.
define('Curator\Session\SESSION_REGEN_TIME', 300);

//Sets the percentage chance the session ID is regenerated (out of 100%) for added security.
//Suggested value is 5  (5%). Set to FALSE to disable this option.
define('Curator\Session\SESSION_REGEN_CHANCE', 5);

//Session ID management. Always use '1'.
define('Curator\Session\SESSION_USE_COOKIES', 1);

//Session ID management #1. Always use '1'.
define('Curator\Session\SESSION_USE_ONLY_COOKIES', 1);

//Cookie lifetime. Suggest '0' (Until browser is closed).
define('Curator\Session\SESSION_COOKIE_LIFETIME', 0);

//Ensures cookie is only accessable via HTTP protocol. Always use '1'.
define('Curator\Session\SESSION_COOKIE_HTTPONLY', 1);

//Attach Session ID to URL. Always use '0'.
define('Curator\Session\SESSION_USE_TRANS_SID', 0);

//Enable strict Session ID mode. Restricts uninitialized Session ID's. Always use '1'.
define('Curator\Session\SESSION_USE_STRICT_MODE', 1);

//File to be used for entropy. Suggest '/dev/urandom' (Unix).
//If server is Windows machine this will default to Windows Random API.
define('Curator\Session\SESSION_ENTROPY_FILE', '/dev/urandom');

//Number of bytes read by the entropy file. Suggest '32' or higher.
define('Curator\Session\SESSION_ENTROPY_LENGTH', 32);

//Number of bits per character. Suggest '5'.
define('Curator\Session\SESSION_HASH_BITS_PER_CHARACTER', 5);

//Sets hash algorithm. Suggest 'sha256'.
define('Curator\Session\SESSION_HASH_FUNCTION', 'sha256');

//Lifetime of session cookie (seconds). Suggest '0' (Until browser is closed).
define('Curator\Session\COOKIE_LIFETIME', 0);

//Path to session cookie. Recommend '/'.
define('Curator\Session\COOKIE_PATH', '/');

//Domain of the cookie. Suggest NULL (Entire domain is covered).
define('Curator\Session\COOKIE_DOMAIN', '');

//Specifies if cookies should be sent over HTTPS.
//Use TRUE if your site operates over HTTPS and FALSE if not.
define('Curator\Session\COOKIE_SECURE', FALSE);

//Forces HTTP only when setting session cookie. Suggest 'TRUE'.
define('Curator\Session\COOKIE_HTTPONLY', TRUE);
?>
