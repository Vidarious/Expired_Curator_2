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

### <a id="config"></a>Configuration
Your Curator Database configuration file (config.php) holds the login credentials to your database. Because of this, it is not uncommon for developers to want to store this file outside of the web server public root directory. This adds an additional layer of protection (in some cases). That is not to say if you keep the config.php file in its script default location it is not secure. 

While the file contents will not be accessable via a browser, if someone should gain direct access to your public directory via malicious means, by storing it outside this directory, the config file and its contents will still be secure. Regardless, if someone should gain administrative access to your web server (the same access you have) all bets are off and your information is exposed.

In some cases, hosting services do not allow you to store files outside of the public directory. Don't be concerned, simply keep the config.php file with the Curator Database class file. If you do decide to store the config.php file in a different location than its default, it is important to update the Curator Database database.php file of its path.

```php
require_once('config.php');
```

### <a id="usage"></a>Usage

### <a id="error"></a>Error Management

##How To Use
This application includes error handling but not error displaying. Read the "Error Handling" part of this readme for more information. In the examples below you will be provided with try/catch included, however these are not required if you don't want to manage database errors.

#####Configuration
The config.php file holds your driver choice and database details. Your first step is to modify these constants. By default, Curator Database includes the config file from the same directory Curator Database (database.php) is found. However for added security it is recommended to store the config.php file in a directory not accessable to other users. If you decide to move the config.php file, be sure to update database.php to reflect that change in path.

#####Name Space
Curator Database lives in the **Curator\\Database** namespace.

```php
namespace Curator\Database;
```

The full path to this class would be:

```php
\Curator\Database
```

#####Include Curator Database
As with every class, you must include the files necessary for the application. In the case of Curator Database you only need to include one.

```php
require_once('database/database.php');
```

#####Open Database Connection
In order to open the connection to your database you must create an object of the class. As Curator Database is designed with a singleton pattern you do this via a method.

```php
$myDatabase = \Curator\Database::GetConnection();
```

---

#####<a id="topMethods"></a>Methods
  - [Prepare a SQL statement](#database1)
  - [Bind values to a prepared statement](#database3)
  - [Execute a prepared statement](#database4)
  - [Retrieve a single row from executed query](#database5)
  - [Retrieve many rows from executed query](#database6)
  - [Retrieve a single column from executed query](#database7)
  - [Get the row count of the executed statement](#database8)
  - [Get the column count of the executed statement](#database10)
  - [Get the ID of the last inserted row for the executed statement](#database9)
  - [Get the prepared statement to use other PDO functions](#database11)

* * *

## <a id="database1"></a>Prepare a SQL statement
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

## <a id="database3"></a>Bind values to a prepared statement

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

## <a id="database4"></a>Execute a prepared statement
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

## <a id="database5"></a>Retrieve a **single row** from executed query
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

## <a id="database6"></a>Retrieve **many rows** from executed query
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

## <a id="database7"></a>Retrieve a **single column** from executed query
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

## <a id="database8"></a>Get the row count of the executed statement
```php
public void database::GetRowCount ([ NULL ] )
```

To find out how many rows were affected by your query use the GetRowCount() method.

```php
$rows = $myDatabase->GetRowCount();
```

[Back to Top](#topMethods)

## <a id="database10"></a>Get the column count of the executed statement
```php
public void database::GetColumnCount ([ NULL ] )
```

To find out how many columns were affected by your query use the GetColumnCount() method.

```php
$columns = $myDatabase->GetColumnCount();
```

[Back to Top](#topMethods)

## <a id="database9"></a>Get the ID of the last inserted row for the executed statement
```php
public void database::GetInsertedID ([ NULL ] )
```

If using the INSERT query you can obtain the last inserted ID by using the GetInsertedID() method.

```php
$lastID = $myDatabase->GetInsertedID();
```

[Back to Top](#topMethods)

## <a id="database11"></a>Get the prepared statement to use other PDO functions.
```php
public void database::GetPreparedStatement ([ NULL ] )
```

For advanced PDO functions you can obtain the prepared statement objaect using the GetPreparedStatement() method.

```php
$pStatement = $myDatabase->GetPreparedStatement();
```

[Back to Top](#topMethods)

---

####Error Handling
Curator Database both logs errors to the PHP server log and throws errors to your application. Curator Database will NOT display any errors or use die() to stop run time. These tasks are left to the parent application developer. Using try{} and catch{} are recommended when performing Curator Database functions.

The thrown error is an Exception() object. All error messages are suppressed to a generic statement which is user friendly. No PHP/PDO error data is displayed. Instead the PHP/PDO data is recorded to the PHP log.

#####Error \#1
Curator Database is unable to connect to the designated database. Check your configuration information and ensure the database server is up and running.

######Error \#2
No database statement was passed to Curator Database to be prepared -> PrepareStatement().

######Error \#3
Bad database query. PDO was unable to prepare the query -> PrepareStatement().

######Error \#4
No parameter to bind to statement -> BindValue().

######Error \#5
Unable to bind provided data to PDO statement. -> BindValue().

######Error \#6
No prepared statement to execute. -> ExecuteQuery().

######Error \#7
Execution of prepared statement failed. -> ExecuteQuery().
