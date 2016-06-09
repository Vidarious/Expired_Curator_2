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
  - [Including the wrapper class](#include)
  - [Namespace](#namespace)
  - [Creating your database object](#create)
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

### <a id="include"></a>Including the wrapper class
As with every class, you must include the files necessary for the application. In the case of Curator Database, you only need to include one.

```php
require_once('database/database.php');
```

[[Back to Top]](#topMethods)

### <a id="namespace"></a>Namespace
Curator Database lives in the **Curator\\Database** namespace.

```php
namespace Curator\Database;
```

To access the class you must use its full name before the method name.

```php
//Namespace or "Path" of the class.
\Curator\Database\App

//Accessing the Class Methods
$objectName = \Curator\Database\App::GetConnection();

//After you have created an object it is now in your namespace and you can access methods normally.
$objectName->MethodName($variable);
```

[[Back to Top]](#topMethods)

### <a id="create"></a>Creating your database object
In order to open the connection to your database you must create an object of the class. As Curator Database is designed with a singleton pattern you do this via a method. The first time you call this method will create an instance of the object. Every time after will retrieve the same instance. This prevents multiple simutaneous connections to your database from a single user.

The method used for creating an instance of this class is called: **GetConnection**.

```php
try
{
    //Open DB Connection.
    $myDatabase = \Curator\Database\App::GetConnection();
}
catch(Throwable $t)
{
    //Your error handling here.
}
```

[Back to Top](#topMethods)

### <a id="prepare"></a>Prepare a SQL statement
```php
public void App::PrepareStatement ([ string $statement = NULL ] )
```

Before you can execute an SQL command you must first prepare the statement. This is done using the **PrepareStatement** method.

```php
$name = 'James';
$age = 30;
$statement = "INSERT INTO TABLE_NAME (name, age) VALUES ($name, $age)";

try
{
    //Open DB Connection.
    $myDatabase = \Curator\Database\App::GetConnection();
    
    //Prepare the SQL query.
    $myDatabase->PrepareStatement($statement);
}
catch(Throwable $t)
{
    //Your error handling here.
}


```

[[Back to Top]](#topMethods)

### <a id="bind"></a>Bind values to a prepared statement

```php
public void App::BindValue ([ string $parameter = NULL, string $value = NULL, string $type = NULL ] )
```

Part of what makes the PDO class so powerful and secure is the fact that it manages the sanitization of user data for you. The previous prepare example did not do this (and that is bad pratice!). Binding variables to your prepared statement is the correct way of injecting data to a SQL query.

This is done by using place holders in the SQL statement which are denoted with semi-colon's followed by using the **BindValue** method. The **BindValue** method replaces your place holders with sanitized data from your variables.

```php
$name = 'James';
$age = 30;
$statement = "INSERT INTO TABLE (name, age) VALUES (:variable1, :variable2)";

try
{
    //Open DB Connection.
    $myDatabase = \Curator\Database\App::GetConnection();
    
    //Prepare the SQL query.
    $myDatabase->PrepareStatement($statement);
    
    //Bind values to the query.
    $myDatabase->BindValue('variable1', $name); //Type not defined.
    $myDatabase->BindValue('variable2', $age, PDO::PARAM_INT); //Type defined.

catch(Throwable $t)
{
    //Process error.
}
```

**NOTE**

>If $type is not specified, the method will first identify the variable passed and properly associate the type:

- Integer: PDO::PARAM_INT
- Boolean: PDO::PARAM_BOOL
- Null (Empty): PDO::PARAM_NULL
- String (Default): PDO::PARAM_STR

[[Back to Top]](#topMethods)

### <a id="execute"></a>Execute a prepared statement
```php
public void App::ExecuteQuery ([ NULL ] )
```

Once you are satified with your query and binded values you are now ready to execute the statement. This is done using the **ExecuteQuery** method.

```php
$name = 'James';
$age = 30;
$statement = "INSERT INTO TABLE (name, age) VALUES (:variable1, :variable2)";

try
{
    //Open DB Connection.
    $myDatabase = \Curator\Database\App::GetConnection();
    
    //Prepare the SQL query.
    $myDatabase->PrepareStatement($statement);
    
    //Bind values to the query.
    $myDatabase->BindValue('variable1', $name); //Type not defined.
    $myDatabase->BindValue('variable2', $age, PDO::PARAM_INT); //Type defined.
    
    //Execute the query.
    $myDatabase->ExecuteQuery();
}
catch(Throwable $t)
{
    //Process error.
}
```

**NOTE**

>This method does not return any value. It executes the prepared statement and performs your query.

[[Back to Top]](#topMethods)

### <a id="getsingle"></a>Retrieve a **single row** from executed query
```php
public void App::GetSingleRow ([ NULL ] )
```

If you are using a SELECT command, once you have executed your statement you can use the **GetSingleRow** method to access the data returned. Each time you run this command it will iterate through each row in the result. This method returns an array and is ideal when you only expect a single row of data to be returned.

```php
$statement = "SELECT * FROM test";

try
{
    //Open DB Connection.
    $myDatabase = \Curator\Database\App::GetConnection();
    
    //Prepare the SQL query.
    $myDatabase->PrepareStatement($statement);
    
    //Execute the query.
    $myDatabase->ExecuteQuery();
    
    //Show the first 3 rows of data.
    var_dump($myDatabase->GetSingleRow());
    var_dump($myDatabase->GetSingleRow());
    var_dump($myDatabase->GetSingleRow());
}
catch(Throwable $t)
{
    //Process error.
}
```

**Will Output**

```php
array(2) 
{ 
  ["name"]=> string(6) "Aprile"
  ["age"]=> string(2) "28"
}
array(2) 
{
  ["name"]=> string(4) "Tina"
  ["age"]=> string(2) "40"
}
array(2)
{
  ["name"]=> string(5) "Debra"
  ["age"]=> string(2) "59"
}
```

**NOTE**

>This data is returned using the fetch() method and FETCH_ASSOC option. Using a loop will allow you to iterate through each row if you are expecting multiple rows. However it is recommended to use **GetAllRows** if you are expected more than one row of data.

[[Back to Top]](#topMethods)

### <a id="getmany"></a>Retrieve **many rows** from executed query
```php
public void App::GetAllRows ([ NULL ] )
```

If you are expecting many rows to be returned from your query it is suggested to use the **GetAllRows** method. This returns all of the data from your query in one multi-dimentional array.

```php
$statement = "SELECT * FROM TABLE_NAME";

try
{
    //Open DB Connection.
    $myDatabase = \Curator\Database\App::GetConnection();
    
    //Prepare the SQL query.
    $myDatabase->PrepareStatement($statement);
    
    //Execute the query.
    $myDatabase->ExecuteQuery();
    
    //Show the returned data.
    var_dump($myDatabase->GetAllRows());
}
catch(Throwable $t)
{
    //Process error.
}
```

**Will Output**

```php
array(5)
{
  [0]=> array(2)
  {
    ["name"]=> string(6) "Aprile"
    ["age"]=> string(2) "28"
  }
  [1]=> array(2)
  {
    ["name"]=> string(4) "Tina"
    ["age"]=> string(2) "40"
  }
  [2]=> array(2)
  {
    ["name"]=> string(5) "Debra"
    ["age"]=> string(2) "59"
  }
  [3]=> array(2)
  {
    ["name"]=> string(5) "James"
    ["age"]=> string(2) "30"
  }
  [4]=> array(2)
  {
    ["name"]=> string(5) "Steve"
    ["age"]=> string(2) "29"
  }
}
```

[[Back to Top]](#topMethods)

### <a id="getcolumn"></a>Retrieve a **single column** from executed query
```php
public void App::GetColumn ([ int $index ] )
```

If using a SELECT query you can retrieve a specific column from the returned data using the **GetColumn($index)** function. Enter the position of the column (0 for the first column and so on) as the $index. Executing this method more than once will iterate through the returned rows.

```php
$statement = "SELECT * FROM TABLE_NAME";

try
{
    //Open DB Connection.
    $myDatabase = \Curator\Database\App::GetConnection();
    
    //Prepare the SQL query.
    $myDatabase->PrepareStatement($statement);
    
    //Execute the query.
    $myDatabase->ExecuteQuery();
    
    //Show only the 0 (name) column.
    var_dump($myDatabase->GetColumn(0));
    var_dump($myDatabase->GetColumn(0));
    var_dump($myDatabase->GetColumn(0));
}
catch(Throwable $t)
{
    //Process error.
}
```

**Will Output**

```php
string(6) "Aprile"
string(4) "Tina"
string(5) "Debra"
```

**NOTE**
>This method uses the fetchColumn() method. If no index is provided it will return the first column (0).

[[Back to Top]](#topMethods)

### <a id="getrowcount"></a>Get the row count of the executed statement
```php
public void App::GetRowCount ([ NULL ] )
```

The **GetRowCount** method will return the total amount of rows returned by your query.

```php
$statement = "SELECT * FROM TABLE_NAME";

try
{
    //Open DB Connection.
    $myDatabase = \Curator\Database\App::GetConnection();
    
    //Prepare the SQL query.
    $myDatabase->PrepareStatement($statement);
    
    //Execute the query.
    $myDatabase->ExecuteQuery();
    
    //Get the row count.
    echo 'Rows: ' . $myDatabase->GetRowCount();
}
catch(Throwable $t)
{
    //Process error.
}
```

**Will Output**

```php
Rows: 5
```

[[Back to Top]](#topMethods)

### <a id="getcolumncount"></a>Get the column count of the executed statement
```php
public void App::GetColumnCount ([ NULL ] )
```

The **GetColumnCount** method will return the total amount of columns returned by your query.

```php
$statement = "SELECT * FROM TABLE_NAME";

try
{
    //Open DB Connection.
    $myDatabase = \Curator\Database\App::GetConnection();
    
    //Prepare the SQL query.
    $myDatabase->PrepareStatement($statement);
    
    //Execute the query.
    $myDatabase->ExecuteQuery();
    
    //Get the row count.
    echo 'Columns: ' . $myDatabase->GetColumnCount();
}
catch(Throwable $t)
{
    //Process error.
}
```

**Will Output**

```php
Columns: 2
```

[[Back to Top]](#topMethods)

### <a id="lastid"></a>Get the ID of the last inserted row for the executed statement
```php
public void App::GetInsertedID ([ NULL ] )
```

When using the INSERT command you can obtain the ID of the last inserted row of data.

```php
$name = 'James';
$age = 30;
$statement = "INSERT INTO TABLE (name, age) VALUES (:variable1, :variable2)";

try
{
    //Open DB Connection.
    $myDatabase = \Curator\Database\App::GetConnection();
    
    //Prepare the SQL query.
    $myDatabase->PrepareStatement($statement);
    
    //Bind values to the query.
    $myDatabase->BindValue('variable1', $name); //Type not defined.
    $myDatabase->BindValue('variable2', $age, PDO::PARAM_INT); //Type defined.
    
    //Execute the query.
    $myDatabase->ExecuteQuery();
    
    //Get last inserted ID.
    echo 'Last ID: ' . $myDatabase->GetInsertedID();
}
catch(Throwable $t)
{
    //Process error.
}
```

**Will Output**

```php
Last ID: 5
```

[[Back to Top]](#topMethods)

### <a id="getstatement"></a>Get the prepared statement to use other PDO functions.
```php
public void App::GetPreparedStatement ([ NULL ] )
```

For advanced users who wish to utilize other PDO functionality, you can get the statement object using the **GetPreparedStatement** method.

```php
$statement = "SELECT * FROM TABLE_NAME";

try
{
    //Open DB Connection.
    $myDatabase = \Curator\Database\App::GetConnection();
    
    //Prepare the SQL query.
    $myDatabase->PrepareStatement($statement);
    
    //Execute the query.
    $myDatabase->ExecuteQuery();
    
    //Get prepared statement object.
    $pStatement = $myDatabase->GetPreparedStatement();
    
    //Custom PDO Code Here ...
}
catch(Throwable $t)
{
    //Process error.
}
```

[[Back to Top]](#topMethods)

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
