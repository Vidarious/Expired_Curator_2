#Curator Database
This class is a wrapper class for the PHP/PDO extension. Originally developed for the Curator application (user management), it operates independantly and fits well into any PHP application interacting with a database. Curator Database offers a simple and secure way to perform your database queries.

#####Version
v1.0

#####Author
James Druhan

---

  - [Configuration](#config)
  - [Usage](#usage)
  - [Error Management](#error)

---

### <a id="config"></a>Configuration
The Curator Database configuration file (config.php) holds the login credentials to your database. Because of this, it is not uncommon for developers to store this file outside of the web server public root directory. This adds an additional layer of protection (in some cases). That is not to say if you keep the config.php file in its script default location it is not secure. 

While the file contents will not be accessable via a browser, if someone were to gain access to your public directory, by storing it outside this directory the config file and its contents will still be secure. However, if someone should gain administrative access to your web server (the same access you have) all bets are off and your information is exposed.

In some cases, hosting services do not allow you to store files outside of the public directory. Don't be concerned, simply keep the config.php file with the Curator Database class file. If you do decide to store the config.php file in a different location than its default, you must update the Curator Database database.php file of its path.

```php
require_once('config.php');
```

---

### <a id="usage"></a>Usage
This wrapper class includes error handling but not error displaying (see [Error Management](#error) for me details). Using Curator Database is simple. Review the following in order to understand each process.

#####<a id="topMethods"></a>Methods
  - [Namespace](#namespace)
  - [Including the wrapper class](#include)
  - [Creating your database object](#include)
  - [Prepare a SQL statement](#prepare)
  - [Bind values to a prepared statement](#bind)
  - [Execute a prepared statement](#execute)
  - [Retrieve a single row from executed query](#getsingle)
  - [Retrieve many rows from executed query](#getmany)
  - [Retrieve a single column from executed query](#getcolumn)
  - [Get the row count of the executed statement](#getrowcount)
  - [Get the column count of the executed statement](#getcolumncount)
  - [Get the ID of the last inserted row for the executed statement](#lastid)
  - [Get the prepared statement to use other PDO functions](#getstatement)

### <a id="namespace"></a>Namespace
Curator Database lives in the **Curator\\Database** namespace.

```php
namespace Curator\Database;
```

The full path to this class would be:

```php
\Curator\Database
```

[Back to Top](#topMethods)

### <a id="include"></a>Including the wrapper class
As with every class, you must include the files necessary for the application. In the case of Curator Database you only need to include one.

```php
require_once('database/database.php');
```

[Back to Top](#topMethods)

### <a id="include"></a>Creating your database object
In order to open the connection to your database you must create an object of the class. As Curator Database is designed with a singleton pattern you do this via a method.

```php
try
{
    $myDatabase = \Curator\Database\App::GetConnection();
}
catch(Throwable $t)
{
    //Your error handling here.
    echo $t->getMessage();
}
```

[Back to Top](#topMethods)

### <a id="prepare"></a>Prepare a SQL statement
```php
public void database::PrepareStatement ([ string $statement = NULL ] )
```

Before you can execute a SQL command you must first prepare the statement.

```php
$name = 'James';
$age = 30;
$statement = "INSERT INTO TABLE (name, age) VALUES ($name, $age)";

try
{
    $myDatabase->prepareStatement($statement);
}
catch (Exception $e)
{
    //Process error.
}


```

[Back to Top](#topMethods)

### <a id="bind"></a>Bind values to a prepared statement

```php
public void database::BindValue ([ string $parameter = NULL, string $value = NULL, string $type = NULL ] )
```

It is highly encouraged to first bind the values to your prepared statement rather then providing direct data. This is done using the BindValue method. It includes providing a parameter in the statement itself using the colon.

```php
$name = 'James';
$age = 30;
$statement = "INSERT INTO TABLE (name, age) VALUES (:name, :age)";

try
{
    $myDatabase->PrepareStatement($statement);

catch (Exception $e)
{
    //Process error.
}
```

Now that you have place holder parameters you must bind your values.

```php
try
{
    $myDatabase->BindValue('name', $name, 'PARAM_STR');
    $myDatabase->BindValue('age', $age, 'PARAM_INT');
}
catch (Exception $e)
{
    //Process error.
}
```

**NOTE**: If $type is not specified, the method will first identify the variable passed and properly associate the type:

- Integer: PARAM_INT
- Boolean: PARAM_BOOL
- Null (Empty): PARAM_NULL
- String (Default): PARAM_STR

[Back to Top](#topMethods)

### <a id="execute"></a>Execute a prepared statement
```php
public void database::ExecuteQuery ([ NULL ] )
```

When you are ready to run your statement you must execute it.

```php
try
{
    $myDatabase->ExecuteQuery();
}
catch (Exception $e)
{
    //Process error.
}
```

**NOTE**: This method does not return any value. It executes the prepared statement and performs your query.

[Back to Top](#topMethods)

### <a id="getsingle"></a>Retrieve a **single row** from executed query
```php
public void database::GetSingleRow ([ NULL ] )
```

If you are using a SELECT command, once you have executed your statement you can use the GetSingleRow() method to access the data returned. Each time you run this command it will iterate through each row in the result. This method returns an array.

```php
$statement = 'SELECT * from USERS';

$myDatabase->PrepareStatment($statement);
$myDatabase->ExecuteQuery();

$data = $myDatabase->GetSingleRow();
```

This data is returned using the fetch() method and FETCH_ASSOC option. Using a loop will allow you to iterate through each row if you are expecting multiple.

```php
print_r($data);

---

Array
(
    [name] => 'James'
    [age] => 30
)
```

[Back to Top](#topMethods)

### <a id="getmany"></a>Retrieve **many rows** from executed query
```php
public void database::GetAllRows ([ NULL ] )
```

If you want to have access to all returned rows of a SELECT statement in one array use the GetAllRows() method.

```php
$statement = 'SELECT * from USERS';

$myDatabase->PrepareStatment($statement);
$myDatabase->ExecuteQuery();

$data = $myDatabase->GetAllRows();
```

This method uses the fetchAll() method with FETCH_ASSOC option.

```php
print_r($data);

---

Array
(
    [0] => Array
    (
        [name] => 'James'
        [age] => 30
    )
    [1] => Array
    (
        [name] => 'Steve'
        [age] => 17
    )
)
```

[Back to Top](#topMethods)

### <a id="getcolumn"></a>Retrieve a **single column** from executed query
```php
public void database::GetColumn ([ int $index ] )
```

If using a SELECT query you can retrieve a specific column from the returned data using the GetColumn($index) function. Enter the position of the column (0 for the first column and so on) as the $index.

```php
$statement = 'SELECT * from USERS';

$myDatabase->PrepareStatment($statement);
$myDatabase->ExecuteQuery();

$data = $myDatabase->GetColumn(1);

echo 'The age is: ' . $data;

---

The age is: 30
```

This command uses the fetchColumn() method. If no index is provided it will return the first column (0). You can run this command repeatily to interate through each row of your results.

[Back to Top](#topMethods)

### <a id="getrowcount"></a>Get the row count of the executed statement
```php
public void database::GetRowCount ([ NULL ] )
```

To find out how many rows were affected by your query use the GetRowCount() method.

```php
$rows = $myDatabase->GetRowCount();
```

[Back to Top](#topMethods)

### <a id="getcolumncount"></a>Get the column count of the executed statement
```php
public void database::GetColumnCount ([ NULL ] )
```

To find out how many columns were affected by your query use the GetColumnCount() method.

```php
$columns = $myDatabase->GetColumnCount();
```

[Back to Top](#topMethods)

### <a id="lastid"></a>Get the ID of the last inserted row for the executed statement
```php
public void database::GetInsertedID ([ NULL ] )
```

If using the INSERT query you can obtain the last inserted ID by using the GetInsertedID() method.

```php
$lastID = $myDatabase->GetInsertedID();
```

[Back to Top](#topMethods)

### <a id="getstatement"></a>Get the prepared statement to use other PDO functions.
```php
public void database::GetPreparedStatement ([ NULL ] )
```

For advanced PDO functions you can obtain the prepared statement objaect using the GetPreparedStatement() method.

```php
$pStatement = $myDatabase->GetPreparedStatement();
```

[Back to Top](#topMethods)

---

### <a id="error"></a>Error Management
Curator Database suppresses PHP & PDO errors and instead throws custom errors of its own to your application. Curator Database will NOT display any errors or use die() to stop run time. This is intentional and prevents revealing potentially sensitive data in your queries or connection information. It is recommended you implement your own error handling processes. As such, using try{} and catch{} are recommended when performing Curator Database functions.

If you decide not to manage Curator Database errors your application will stop operating silently (no messages).

**PHP 7 Note**

>All Curator Database methods will throw Error() objects. This is a new PHP 7 base class and is caught by using 'Throwable'.

```php
try
{
    //Curator Database Method
}
catch(Throwable $t)
{
    //Your error handling here.
    echo $t->getMessage();
}
```
