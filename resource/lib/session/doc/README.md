# Curator Session

This class manages your applications session and cookie needs. Curator Session ensures your sessions and cookies are secure and created with the appropriate configuration that match your site requirements. Feature rich and customizable, Curator Session allows you to concentrate on your application with confidence that your sessions and cookies are ready when you need them.

###### Features
+ Easy Session & Cookie Configuration
+ User IP Validation
+ User Browser Validation
+ Session Timeout
+ Session ID Regeneration
+ Quick One Way Encryption
+ Expandable Get/Set Session & Cookie Variables
+ Secure Session & Cookie Destory

---

### How To Use

Your first course of action is to configure Curator Session by modifying the **config.php** file. The configuration options are explained in the Configuration section of this readme. Once configured, you must include the application file into your page(s).

```php
require_once('session/App.php');
```

Finally you will then create your Curator Session object. This is done using a method as this class is designed in the Singleton pattern.

```php
$mySessionObject = Curator\Session\App::GetSession();
```

**NOTICE:**
>This class is namespaced to Curator\Session for your convience. You will only need to worry about the namespace for the inital object creation.

####<a id="topMethods"></a>Methods
- [Start A New Session](#startsession)
- [Encode Data](#encode)
- [Get Session Value](#getvalue)
- [Set Session Value](#setvalue)
- [Get Cookie Value](#getcookie)
- [Set Cookie Value](#setcookie)
- [Delete Cookie Value](#deletecookie)
- [Destroy Session](#destroysession)
- [Destroy Cookies](#destroycookies)

##### <a id="startsession"></a>Start A New Session

```php
public void App::NewSession ([])
```

NewSession destroys the previous session and creates a new one. This is not necessary to use unless you want to force the creation of a new session (creating the Curator Session object creates a session by default).

```php
$mySessionObject->NewSession();
```

##### <a id="encode"></a>Encode Data

```php
public string App::Encode ([ string $value = NULL ])
```

The Encode method allows you to encrypt data. This is a one way encryption using the site salt you set in the configuration. This data cannot be decrypted. This means that data you send to Encode will be lost but can be used for comparison later.

**TIP**
>While this method may not be the absolute best for encrypting user passwords, its a start. NEVER store a users password in plain text. Encrypt it then store it. A password should never need to be decrypted. Just compare a users password attempt with the encrypted password - if its a match, grant access.

```php
$encryptedData = $mySessionObject->Encode('Private Data');
```

[Back to Top](#topMethods)

##### <a id="getvalue"></a>Get Session Value

```php
public static string App::GetValue ([ string $variable = NULL ])
```

GetValue returns the value of the passed session variable name. This is the same as accessing the variable directly using $_SESSION['Variable']. However, the purpose of this method is to provide you with a way to create a wrapper and customize this action.

```php
$myData = $mySessionObject::GetValue('myVariable');
```

[Back to Top](#topMethods)

##### <a id="setvalue"></a>Set Session Value

```php
public static void App::SetValue ([ string $variable = NULL ], [ string $value = NULL ])
```

SetValue assigns data to the session variable of your choice. This is the same as assigning directly with $_SESSION['Variable']. However the purpose of this method is to provide you with a way to create a wrapper and customize this action.

```php
$mySessionObject::SetValue('myVariable', 'Some Data Here');
```

[Back to Top](#topMethods)

##### <a id="getcookie"></a>Get Cookie Value

```php
public static string App::GetCookie ([ string $name = NULL ])
```

GetCookie returns the data assigned to the passed cookie name.

```php
$cookieData = $mySessionObject::GetCookie('myCookie');
```

[Back to Top](#topMethods)

##### <a id="setcookie"></a>Set Cookie Value

```php
public static bool App::SetCookie
  (
      [ string $name     = NULL ],
      [ string $value    = NULL ],
      [ string $expire   = COOKIE_LIFETIME ],
      [ string $path     = COOKIE_PATH ],
      [ string $domain   = COOKIE_DOMAIN ],
      [ string $secure   = COOKIE_SECURE ],
      [ string $httpOnly = COOKIE_HTTPONLY ],
  )
```

SetCookie allows you to create a cookie. Only the first 2 parameters are required (name and value). The other variables will be taken from the configuration data. However, you may override these as you see fit.

```php
$mySessionObject::SetCookie('myCookie', 'Some Data Here');
```

[Back to Top](#topMethods)

##### <a id="deletecookie"></a>Delete Cookie Value

```php
public static bool App::DeleteCookie ([ string $name = NULL ])
```

DeleteCookie allows you to delete cookies. Pass the name of cookie to delete. You will receive back TRUE if deletion was OK and FALSE if not.

```php
$mySessionObject::DeleteCookie('myCookie');
```

[Back to Top](#topMethods)

##### <a id="destroysession"></a>Destroy Session

```php
public static void App::DestroySession ([])
```

DestroySession completely deletes/destroys your existing session.

```php
$mySessionObject::DestroySession();
```

[Back to Top](#topMethods)

##### <a id="destroycookies"></a>Destroy Cookies

```php
public static void App::DestroyCookie ([])
```

DestroyCookie completely deletes/destroys your existing cookies.

```php
$mySessionObject::DestroyCookie();
```

[Back to Top](#topMethods)

---

#### Configuration
Curator Session has a large number of configuration options. Not all need to be customized but the option is available to you regardless.

#####SESSION_NAME [STRING]
Assign a name to your sessions. This name will be the first part of each application session variable such as "MYSITESESSION_status".

>Default Value: 'MYSITESESSION'

#####IP_VALIDATION [BOOL]
TRUE or FALSE indicates if IP validation is enabled or disabled. This will check if the users IP stays consistant from page to page. As capturing the users IP can be difficult with some users, when an IP cannot be obtained through the server variables Curator Session will allow the user to continue without creating a new session. While this negates this feature completely it allows your sessions to continue running smoothly for all users. This may fool a number of people trying to hijack session ID's but it wont do much against more sophisticated hackers.

>Default Value: TRUE

#####SESSION_IDLE_TIME [INT]
Set the amount of time (in seconds) you will allow your users to idle until their session is destroyed and created again. For example if set to 1800 (5 minutes) and the user is idle for 6 minutes, their session will be recreated on their next page load.

>Default Value: 1800

#####SESSION_USERAGENT_CHECK [BOOL]
TRUE or FALSE indicates if the user browser is verified on each page load. This is an extra layer of security to help reduce session hijacking.

>Default Value: TRUE

#####SESSION_SITE_SALT [STRING]
Create a unique site session salt. This is used in the encode, IP and browser processes.

>Default Value: 'JKfjknfjkfn389f8fhf38FHh830Fq3'

#####SESSION_REGEN_TIME [INT]
Set the maximum of time that can pass for a user until the session ID is regenerated. This value is in seconds. Regenerating the session ID occasionally is another method of reducing session hijacking.

>Default Value: 300

#####SESSION_REGEN_CHANCE [INT]
Enter a value from 0-100 which represents the percent chance the session will be regenerated on page load. This adds a unpredictable chance that the session ID will be regenerated on every page load.

>Default Value: 5

#####SESSION_USE_COOKIES [INT]
Specifies if the server will use cookies to store the session ID on the clide side. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.use-cookies)

>Default Value: 1

#####SESSION_USE_ONLY_COOKIES [INT]
Specifies if the server will **only** use cookies to store the session ID on the clide side. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.use-only-cookies)

>Default Value: 1

#####SESSION_COOKIE_LIFETIME [INT]
Specifies the lifetime of the cookies in seconds. '0' means until the browser is closed. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.cookie-lifetime)

>Default Value: 0

#####SESSION_COOKIE_HTTPONLY [INT]
Specifies if the cookie is accessible only through the HTTP protocol. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.cookie-httponly)

>Default Value: 1

#####SESSION_USE_TRANS_SID [INT]
Specifies if transparent Session ID is enabled or not. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.use-trans-sid)

>Default Value: 0

#####SESSION_USE_STRICT_MODE [INT]
Specifies if the server will use strict session ID mode. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.use-strict-mode)

>Default Value: 1

#####SESSION_ENTROPY_FILE [STRING]
Specifies an external source to be used in the session ID creation. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.entropy-file)

>Default Value: '/dev/urandom'

#####SESSION_ENTROPY_LENGTH [STRING]
Specifies the number of bytes which will be read from the specified entropy file. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.entropy-length)

>Default Value: '32'

#####SESSION_HASH_BITS_PER_CHARACTER [INT]
Specifies the number of bits stored in each character when converting the binary hash data to something readable. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.hash-bits-per-character)

>Default Value: 5

#####SESSION_HASH_FUNCTION [STRING]
Specifies the hash algorithm to be used to generate the session ID's. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.hash-function)

>Default Value: 'sha256'

#####COOKIE_LIFETIME [INT]
Specifies the lifetime of the cookie in seconds which is sent to the browser. '0' means until the browser is closed.[More Information](http://php.net/manual/en/session.configuration.php#ini.session.cookie-lifetime)

>Default Value: 0

#####COOKIE_PATH [STRING]
Specifies the path to set in the session cookie. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.cookie-path)

>Default Value: '/'

#####COOKIE_DOMAIN [STRING]
Specifies the domain to set in the session cookie. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.cookie-domain)

>Default Value: ''

#####COOKIE_SECURE [BOOL]
Specifies whether cookies should be sent over secure connections or not. Enable this if your site uses HTTPS. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.cookie-secure)

>Default Value: FALSE

#####COOKIE_HTTPONLY [BOOL]
Specifies if the cookie is acessible only through the HTTP protocol. [More Information](http://php.net/manual/en/session.configuration.php#ini.session.cookie-httponly)

>Default Value: TRUE
