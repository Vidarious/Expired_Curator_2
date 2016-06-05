#Curator Database

####Author
James Druhan

####Start Date
January 16, 2016

####First Rehash
June 4, 2016

---

####Description
Curator Database is a wrapper application for the PDO class. Designed with the singleton pattern, only one instances of the Curator Database object can exist, thus preventing multiple database connections for a single user.

####How To Use
Using Curator Database is very simple. This application includes error handling but not error displaying. Read the "Error Handling" part of this readme for more information. In the examples below you will be provided with try/catch included however these are not required if you don't want to manage database errors.

#####Configuration

#####Name Space
Curator Database lives in the Curator\\Database namespace.

```php
namespace Curator\Database;
```

The full path to this class would be:

```php
\Curator\Database
```

#####Include Curator Database
As with every class, you must first include the files necessary for the application. In the case of Curator Database you only need to include one.

```php
require_once('database/database.php');
```

#####Open Database Connection
In order to open the connection to your database you must create an object of the class. As Curator Database is designed with a singleton pattern you do this via a function.

```php
$myDatabase = \Curator\Database::GetConnection();
```

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
