# <a id="topguide"></a>Curator How To Guide

####Classes
- [Database](#database)
  - [Create database object](#database1)
  - [Prepare a SQL statement](#database2)
  - [Bind values to a prepared statement](#database3)
  - [Execute a prepared statement](#database4)
  - [Retrieve a single row result from executed query](#database5)
  - [Retrieve a many row result from executed query](#database6)
  - [Retrieve a single column result from executed query](#database7)
  - [Get the row count of the executed statement](#database8)
  - [Get the ID of the last inserted row for the executed statement](#database9)
- [Lanugage](#language)
  - [Create a language object](#language1)
  - [Load class language file](#language2)
- [Session](#session)
  - [Get user IP address](#session1)
- [Log](#log)

* * *

## <a id="database"></a>Database
This class is in the **\Curator\Classes** namespace.

#####Create database object<a id="database1"></a>
```php
$CURATOR_DB = CLASSES\Database::getConnection();
```

[Back to Top](#topguide)

##### <a id="database2"></a>Prepare a SQL statement
```php
$statement = "INSERT INTO TABLE (name, value) VALUES (:name, :value)";
$CURATOR_DB->prepareStatement($statement);
```

[Back to Top](#topguide)

##### <a id="database3"></a>Bind values to a prepared statement
```php
$parameter = "name";
$value = "John";

$CURATOR_DB->bindValue($parameter, $value, \PDO::PARAM_INT);
```
OR
```php
$CURATOR_DB->bindValue("name", "John", \PDO::PARAM_INT);
```

**NOTE**: If $parameterType is not set the method will first identify the variable passed and properly associate the type:
- PARAM_INT
- PARAM_BOOL
- PARAM_NULL
- PARAM_STR

[Back to Top](#topguide)

##### <a id="database4"></a>Execute a prepared statement
```php
$CURATOR_DB->executeStatement();
```

**NOTE**: This method does not return any value. It executes the prepared statement and assigns it to a private object variable.

[Back to Top](#topguide)

##### <a id="database5"></a>Retrieve a **single row** result from executed query
```php
$data = $CURATOR_DB->getResultSingle();
```

[Back to Top](#topguide)

##### <a id="database6"></a>Retrieve a **many row** result from executed query
```php
$data = $CURATOR_DB->getResultMany();
```

[Back to Top](#topguide)

##### <a id="database7"></a>Retrieve a single **column** result from executed query
```php
$data = $CURATOR_DB->getResultColumn(); //Returns the first single result value.
```

OR

```php
$data = $CURATOR_DB->getResultColumn(3); //Returns the 3rd single result value.
```

[Back to Top](#topguide)

##### <a id="database8"></a>Get the row count of the executed statement
```php
$data = $CURATOR_DB->getRowCount();
```

[Back to Top](#topguide)

##### <a id="database9"></a>Get the ID of the last inserted row for the executed statement
```php
$data = $CURATOR_DB->getInsertedID();
```

[Back to Top](#topguide)

* * *

## <a id="language"></a>Language
This class is in the **\Curator\Classes** namespace.

##### <a id="language1"></a>Create a language object
```php
$myLanguage = "en_CA";
$LANG = CLASSES\Language::getLanguage($myLanguage);
```

**NOTE**: If the $myLanguage variable is not set, Curator will set the language to it's system default.

[Back to Top](#topguide)

##### <a id="language2"></a>Load class language file.
This is an easy way to load your class language files. For Curator these language files are typically for logs (errors and warnings). Path to class language file is *[Curator Language Path]/language_LOCALE/class/__CLASSNAME__.php*.
```php
$this->Language->loadClassLanguage(__CLASS__);
```

**NOTE**: If a language file does not exist no errors or logs will be generated.

[Back to Top](#topguide)

* * *

## <a id="session"></a>Session
This class is in the **\Curator\Classes** namespace.

#####  <a id="session1"></a>Get user IP address
```php
echo $CURATOR_SESSION->userIP;
```

[Back to Top](#topguide)

* * *

## <a id="log"></a>Log
This class is in the **\Curator\Classes** namespace.

[Back to Top](#topguide)