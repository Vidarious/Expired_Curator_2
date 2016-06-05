# <a id="topguide"></a>Curator - How To Guide - *Database Class*

The database class is used by The Curator to interact with your SQL database in order to manage application and user data. You can utilize the database object created by The Curator to perform all your necessary database actions: *Prepare a Statement*, *Bind Values*, *Execute Statement*, *Retrieve Results* (*Single Value*, *Many Rows*/*Columns*, *Many Columns*), *Row Count* and *Inserted ID*. All database actions are performed with secure [*PDO*](http://php.net/manual/en/book.pdo.php "Go to: PHP Manual").

####Namespace
```php
namespace Curator\Classes;
```

The database class belongs to the Curator\Classes PHP namespace. This means the full path the this class would be:

```php
\Curator\Classes\Database
```
* * *
####Requirements
The database class requires the [**Language**](Language.md "Go to: Curator Language Class") & [**Log**](Log.md "Go to: Curator Log Class") classes in order to log any error messages in the admin designated language. These class files are not loaded in this class file but rather loaded with the PHP [*spl_autoload_register*](http://php.net/manual/en/function.spl-autoload-register.php "Go to: PHP Manual") function from The Curator.

In addition to the Language and Log classes the Database class requires a number of global application variables. Some of these are globally declared while others are found in $_SESSION[].
* * *
####Object Creation
```php
$_CURATOR['DATABASE'] = CLASSES\Database::getConnection();
```
By default, The Curator creates an object for this class when the application is included in your site. The object **$_CURATOR['DATABASE']** can also be used by your own application and scripts in order to access your SQL database.

The database class is designed as a [*singleton*](http://www.phptherightway.com/pages/Design-Patterns.html "Go to: Design Patterns"). In short, this means that you cannot have multiple instances of this class. If you create your own object, the class will return the previously instantiated class object. This ensures only one connection to your database exists.

```php
//Create your own database object.
$myDatabaseVariable = CLASSES\Database::getConnection();
//Returns the same instance of the class as $_CURATOR['DATABASE'].
```
* * *
####<a id="topMethods"></a>Methods
  - [Prepare a SQL statement](#database1)
  - [Bind values to a prepared statement](#database3)
  - [Execute a prepared statement](#database4)
  - [Retrieve a single row result from executed query](#database5)
  - [Retrieve a many row result from executed query](#database6)
  - [Retrieve a single column result from executed query](#database7)
  - [Get the row count of the executed statement](#database8)
  - [Get the ID of the last inserted row for the executed statement](#database9)

* * *

##### <a id="database1"></a>Prepare a SQL statement
```php
public void Database::prepareStatement ([ string $statement = NULL ] )
```

```php
$statement = "INSERT INTO TABLE (name, value) VALUES (:name, :value)";
$CURATOR_DB->prepareStatement($statement);
```

[Back to Top](#topMethods)

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
