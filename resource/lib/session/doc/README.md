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
- [Encode Data](#encode)
- [Get Session Value](#getvalue)
- [Set Session Value](#setvalue)
- [Get Cookie Value](#getcookie)
- [Set Cookie Value](#setcookie)
- [Delete Cookie Value](#deletecookie)
- [Destroy Session](#destroysession)
- [Destroy Cookies](#destroycookies)

##### <a id="encode"></a>Encode Data

```php
public string App::Encode ([ string $value = NULL ])
```

The Encode method allows you to encrypt data. This is a one way encryption using the site salt you set in the configuration. This data cannot be decrypted. This means that data you send to Encode will be lost but can be used for comparison later.

```php
$mySessionObject->Encode('Private Data');
```

[Back to Top](#topMethods)

##### <a id="getvalue"></a>Get Session Value

```php
public static string App::GetValue ([ string $variable = NULL ])
```

GetValue returns the value of the passed session variable name. This is the same as accessing the variable directly using $_SESSION['Variable']. However, the purpose of this method is to provide you with a way to create a wrapper and customize this action.

```php
$mySessionObject::GetValue('myVariable');
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
$mySessionObject::GetCookie('myCookie');
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

SetCookie allows you to create a cookie. Only the first type parameters are required (name and value). The other variables will be taken from the configuration data. However you may override these as you see fit.

```php
$mySessionObject::SetCookie('myCookie', 'Some Data Here');
```

[Back to Top](#topMethods)

##### <a id="deletecookie"></a>Delete Cookie Value

```php
public static bool App::DeleteCookie ([ string $name = NULL ])
```

DeleteCookie allows you to delete cookies.

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

#####SESSION_NAME
Desc

>Default Value: 'MYSITESESSION'

#####IP_VALIDATION
Desc

>Default Value: TRUE

#####SESSION_IDLE_TIME
Desc

>Default Value: 1800

#####SESSION_USERAGENT_CHECK
Desc

>Default Value: TRUE

#####SESSION_SITE_SALT
Desc

>Default Value: 'JKfjknfjkfn389f8fhf38FHh830Fq3'

#####SESSION_REGEN_TIME
Desc

>Default Value: 300

#####SESSION_REGEN_CHANCE
Desc

>Default Value: 5

#####SESSION_USE_COOKIES
Desc

>Default Value: 1

#####SESSION_USE_ONLY_COOKIES
Desc

>Default Value: 1

#####SESSION_COOKIE_LIFETIME
Desc

>Default Value: 0

#####SESSION_COOKIE_HTTPONLY
Desc

>Default Value: 1

#####SESSION_USE_TRANS_SID
Desc

>Default Value: 0

#####SESSION_USE_STRICT_MODE
Desc

>Default Value: 1

#####SESSION_ENTROPY_FILE
Desc

>Default Value: 

#####SESSION_ENTROPY_LENGTH
Desc

>Default Value: '/dev/urandom'

#####SESSION_HASH_BITS_PER_CHARACTER
Desc

>Default Value: 32

#####SESSION_HASH_FUNCTION
Desc

>Default Value: 'sha256'

#####COOKIE_LIFETIME
Desc

>Default Value: 0

#####COOKIE_PATH
Desc

>Default Value: '/'

#####COOKIE_DOMAIN
Desc

>Default Value: ''

#####COOKIE_SECURE
Desc

>Default Value: FALSE

#####COOKIE_HTTPONLY
Desc

>Default Value: TRUE
