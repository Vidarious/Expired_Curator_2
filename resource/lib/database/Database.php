<?php
/*
 * Curator\Database is a library for managing database communication for any PHP based application.
 *
 * Naming Practices
 * ----------------
 * Classes    -> lower_case
 * Methods    -> PascalCase
 * Properties -> camelCase
 * Constants  -> UPPER_CASE
 *
 * PHP Version 7.0.6
 *
 * @package    Curator\Database
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
namespace Curator\Database;

class database
{
    //Class Properties (Object).
    private $databaseConnection = NULL;
    private $preparedStatement  = NULL;

    //Class Initalization. Singleton design.
    protected function __construct()
    {
        $pdoServerString = GetServerString();
        $pdoOptionString = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);

        try
        {
            $this->databaseConnection = new \PDO($pdoServerString, USERNAME, PASSWORD, $pdoOptionString);
        }
        catch(PDOException $pdoError)
        {
            error_log('Curator\Database generated an error: ' . $pdoError);
        }
    }

//Singleton design.
private function __clone() {}

//Singleton design.
private function __wakeup() {}

//Builds PDO connection string depending on PDO driver selected.
private function GetServerString()
{
    $serverString = NULL;

    switch(DRIVER)
    {
        case 'MySQL':
            $serverString = 'mysql:host=' . HOST . ';dbname=' . DATABASE_NAME;
            break;
    }

    return($serverString);
}

//Returns the singleton instance of the database connection. Singleton design.
public static function getConnection()
{
static $pdoInstance = NULL;

if($pdoInstance === NULL)
{
$pdoInstance = new static();
}

return $pdoInstance;
}

//Prepares SQL query using PDO.
public function prepareStatement($statement = NULL)
{
if(!empty($statement))
{
if(!$this->preparedStatement = $this->Connection->prepare($statement))
{
$logMessage = new \Curator\Application\Log(__CLASS__, __METHOD__);
$logMessage->saveError(LANG\ERROR_PREPARE . $statment);
}
}
}

//Binds value to parameter with the passed type.
public function bindValue($parameter = NULL, $value = NULL, $type = NULL)
{
if(!empty($parameter) && !empty($value))
{
if(empty($type))
{
$type = self::getType($value);
}

if(!$this->preparedStatement->bindValue($parameter, $value, $type))
{
$logMessage = new \Curator\Application\Log(__CLASS__, __METHOD__);
$logMessage->saveError(LANG\ERROR_BIND . 'Parameter: ' . $parameter . ', Value: ' . $value . ', Type: ' . $type);
}
}
}

//Determine the type of the value (INT/BOOL/STR) and return.
private function getType($value = NULL)
{
switch(TRUE)
{
case is_int($value) :
$type = \PDO::PARAM_INT;
break;
case is_bool($value):
$type = \PDO::PARAM_BOOL;
break;
case is_null($value):
$type = \PDO::PARAM_NULL;
break;
default:
$type = \PDO::PARAM_STR;
}

return $type;
}

//Execute the PDO prepared statement.
public function executeQuery()
{
if(empty($this->preparedStatement) || empty($this->preparedStatement->execute()))
{
$logMessage = new \Curator\Application\Log(__CLASS__, __METHOD__);
$logMessage->saveError(LANG\ERROR_EXECUTE);
}
}

//Executes a SQL query (SELECT) and returns a single row.
public function getResultSingle()
{
return($this->preparedStatement->fetch(\PDO::FETCH_ASSOC));
}

//Executes a SQL query (SELECT) and returns many rows.
public function getResultMany()
{
return($this->preparedStatement->fetchAll(\PDO::FETCH_ASSOC));
}

//Executes a SQL query (SELECT) and returns a single column item with $row representing which result row.
public function getResultColumn($row = NULL)
{
if($row)
{
return($this->preparedStatement->fetchColumn($row));
}

return($this->preparedStatement->fetchAll(\PDO::FETCH_COLUMN));
}

//Returns the row count for the executed query result.
public function getRowCount()
{
return($this->preparedStatement->rowCount());
}

//Returns the row ID of the previously inserted record.
public function getInsertedID()
{
return($this->Connection->lastInsertId());
}
}
?>
