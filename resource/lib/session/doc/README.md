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

#### How To Use

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

#####<a id="topMethods"></a>Methods
- [Validate User IP](#validateip)
- [Encode Data](#encode)
- [Get Session Value](#getvalue)
- [Set Session Value](#setvalue)
- [Get Cookie Value](#getcookie)
- [Set Cookie Value](#setcookie)
- [Delete Cookie Value](#deletecookie)
- [Destroy Session](#destroysession)
- [Destroy Cookies](#destroycookies)

##### <a id="validateip"></a>Validate User IP


[Back to Top](#topMethods)

##### <a id="encode"></a>Encode Data


[Back to Top](#topMethods)

##### <a id="getvalue"></a>Get Session Value


[Back to Top](#topMethods)

##### <a id="setvalue"></a>Set Session Value


[Back to Top](#topMethods)

##### <a id="getcookie"></a>Get Cookie Value


[Back to Top](#topMethods)

##### <a id="setcookie"></a>Set Cookie Value


[Back to Top](#topMethods)

##### <a id="deletecookie"></a>Delete Cookie Value


[Back to Top](#topMethods)

##### <a id="destroysession"></a>Destroy Session


[Back to Top](#topMethods)

##### <a id="destroycookies"></a>Destroy Cookies


[Back to Top](#topMethods)

---

#### Configuration

#### Options
