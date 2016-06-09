<?php
/*
 * Curator Database is a library for managing database communication for any PHP based application.
 *
 * Naming Convention
 * -----------------
 * Classes    -> PascalCase
 * Methods    -> PascalCase
 * Properties -> camelCase
 * Constants  -> UPPER_CASE
 *
 * Written with PHP Version 7.0.6
 *
 * @package    Curator Database
 * @author     James Druhan <jdruhan.home@gmail.com>
 * @copyright  2016 James Druhan
 * @version    1.0
 */
namespace Curator\Database;

//Curator Database configuration file. If this file is moved outside the PUBLIC accessable directory update this path.
require_once('config.php');

class App
{
    //Class Properties (Object).
    private $databaseConnection = NULL;
    private $preparedStatement  = NULL;

    //Class Initalization. Singleton design.
    protected function __construct()
    {
        $pdoServerString = self::GetServerString();
        $pdoOptionString = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);

        try
        {
            //Open connection to database.
            $this->databaseConnection = new \PDO($pdoServerString, USERNAME, PASSWORD, $pdoOptionString);
        }
        catch (\Throwable $t)
        {
            throw new \Error('Unable to connect to database. Check configuration data.');
        }
    }

    //Singleton design.
    private function __clone() {}
    private function __wakeup() {}

    //Builds PDO connection string depending on PDO driver selected.
    private function GetServerString()
    {
        $serverString = NULL;

        switch(DRIVER)
        {
            case 'MySQL':
                return('mysql:host=' . HOST . ';dbname=' . DATABASE_NAME);
        }
    }

    //Returns the singleton instance of the database connection.
    public static function GetConnection()
    {
        static $pdoInstance = NULL;

        if($pdoInstance === NULL)
        {
            $pdoInstance = new static();
        }

        return $pdoInstance;
    }

    //Prepares database query using PDO.
    public function PrepareStatement($statement = NULL)
    {
        //Verify data has been provided to the function.
        if(empty($statement))
        {
            throw new \Error('No query found to prepare.');
        }
        else
        {
            //Prepare the statement.
            try
            {
                $this->preparedStatement = $this->databaseConnection->prepare($statement);
            }
            catch (\Throwable $pdoError)
            {
                throw new \Error('Unable to prepare query. Verify syntax.');
            }
        }
    }

    //Binds value to prepared statement parameter.
    public function BindValue($parameter = NULL, $value = NULL, $type = NULL)
    {
        //Assign a data type if one is not provided.
        if(empty($type))
        {
            $type = self::GetType($value);
        }

        //Bind the parameter to the prepared statement.
        try
        {
            $this->preparedStatement->bindValue($parameter, $value, $type);
        }
        catch (\Throwable $pdoError)
        {
            throw new \Error('Unable to bind data to query. Verify syntax and variable data.');
        }
    }

    //Determine the parameter data type (INT/BOOL/NULL/STR).
    private function GetType($value = NULL)
    {
        switch(TRUE)
        {
            case is_int($value) :
                return(\PDO::PARAM_INT);
            case is_bool($value):
                return(\PDO::PARAM_BOOL);
            case is_null($value):
                return(\PDO::PARAM_NULL);
            default:
                return(\PDO::PARAM_STR);
        }
    }

    //Executes the prepared statement.
    public function ExecuteQuery()
    {
        if(empty($this->preparedStatement))
        {
            throw new \Error('Query to execute.');
        }

        try
        {
            $this->preparedStatement->execute();
        }
        catch (\Throwable $pdoError)
        {
            throw new \Error('Unable to execute the query. Verify statement.');
        }
    }

    //Returns an array which contains the next row from the executed statement.
    public function GetSingleRow()
    {
        return($this->preparedStatement->fetch(\PDO::FETCH_ASSOC));
    }

    //Returns an array which contains all rows of the executed statement.
    public function GetAllRows()
    {
        return($this->preparedStatement->fetchAll(\PDO::FETCH_ASSOC));
    }

    //Returns the requested column from the next result row.
    public function GetColumn($index = NULL)
    {
        return($this->preparedStatement->fetchColumn($index));
    }

    //Returns the number of rows affected by the executed statement.
    public function GetRowCount()
    {
        return($this->preparedStatement->rowCount());
    }

    //Returns the number of columns affected by the executed statement.
    public function GetColumnCount()
    {
        return($this->preparedStatement->columnCount());
    }

    //Returns the ID of the last inserted row.
    public function GetInsertedID()
    {
        return($this->databaseConnection->lastInsertId());
    }

    //Returns the prepared statement for manual PDO control.
    public function GetPreparedStatement()
    {
        return($this->preparedStatement);
    }
}
?>
