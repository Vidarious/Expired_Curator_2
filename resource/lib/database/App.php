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
 * Requires PHP 7+
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
    private function GetServerString() : STRING
    {
        switch(DRIVER)
        {
            case 'MySQL':
                return('mysql:host=' . HOST . ';dbname=' . DATABASE_NAME);
            default:
                return('mysql:host=' . HOST . ';dbname=' . DATABASE_NAME);
        }
    }

    //Returns the singleton instance of the database connection.
    public static function GetConnection() : SELF
    {
        static $dbInstance = NULL;

        if($dbInstance === NULL)
        {
            $dbInstance = new static();
        }

        return $dbInstance;
    }

    //Prepares database query using PDO.
    public function PrepareStatement(STRING $statement = NULL) : BOOL
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

                //Check to ensure statement was correctly prepared.
                if($this->preparedStatement === FALSE)
                {
                    throw new \Error('Unable to prepare query. Verify syntax.');
                }
            }
            catch (\Throwable $pdoError)
            {
                throw new \Error('Unable to prepare query. Verify syntax.');
            }

            return(TRUE);
        }
    }

    //Binds value to prepared statement parameter.
    public function BindValue($parameter = NULL, $value = NULL, INT $type = NULL) : BOOL
    {
        //Assign a data type if one is not provided.
        if(empty($type))
        {
            $type = self::GetType($value);
        }

        //Bind the parameter to the prepared statement.
        try
        {
            $binded = $this->preparedStatement->bindValue($parameter, $value, $type);

            //Check to ensure the value was correctly binded.
            if($binded === FALSE)
            {
                throw new \Error('Unable to bind data to query. Verify syntax and variable data.');
            }
        }
        catch (\Throwable $pdoError)
        {
            throw new \Error('Unable to bind data to query. Verify syntax and variable data.');
        }

        return(TRUE);
    }

    //Determine the parameter data type (INT/BOOL/NULL/STR).
    private function GetType($value = NULL) : INT
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
    public function ExecuteQuery() : BOOL
    {
        if(empty($this->preparedStatement))
        {
            throw new \Error('There is no Query to execute.');
        }

        try
        {
            $executed = $this->preparedStatement->execute();

            //Check to ensure the query was executed correctly.
            if($executed === FALSE)
            {
                throw new \Error('Unable to execute the query. Verify statement.');
            }
        }
        catch (\Throwable $pdoError)
        {
            throw new \Error('Unable to execute the query. Verify statement.');
        }

        return(TRUE);
    }

    //Returns an array which contains the next row from the executed statement.
    public function GetSingleRow() : ARRAY
    {
        return($this->preparedStatement->fetch(\PDO::FETCH_ASSOC));
    }

    //Returns an array which contains all rows of the executed statement.
    public function GetAllRows() : ARRAY
    {
        return($this->preparedStatement->fetchAll(\PDO::FETCH_ASSOC));
    }

    //Returns the requested column from the next result row.
    public function GetColumn($index = NULL)
    {
        return($this->preparedStatement->fetchColumn($index));
    }

    //Returns the number of rows affected by the executed statement.
    public function GetRowCount() : INT
    {
        return($this->preparedStatement->rowCount());
    }

    //Returns the number of columns affected by the executed statement.
    public function GetColumnCount() : INT
    {
        return($this->preparedStatement->columnCount());
    }

    //Returns the ID of the last inserted row.
    public function GetInsertedID() : INT
    {
        return($this->databaseConnection->lastInsertId());
    }

    //Returns the prepared statement for manual PDO control.
    public function GetPreparedStatement() : \PDOStatement
    {
        return($this->preparedStatement);
    }
}
?>
