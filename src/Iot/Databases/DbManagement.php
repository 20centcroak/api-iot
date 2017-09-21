<?php

namespace Croak\Iot\Databases;
use Croak\Iot\Exceptions\DataBaseException;

/**
 * Manages the database: connection, read/write
 * it uses PDO for a global management and an easy change to another database type
 * It makes this Object a generic type for database management
 */
class DbManagement
{
    /**
    *@var String    directory of database record
    */
    const DB_FOLDER = "../db";
     /**
    *@var String    url of database 
    */
    private const URL = 'sqlite:'.DbManagement::DB_FOLDER.'/iotDB.sqlite';
    /**
    *@var PDO       object to address the database
    */
    private $pdo;

    /** 
    * private constructor : building the object
    * should be done by calling connect()
    * @param PDO $pdo        the pdo object built in connect() function
    */
    private function __construct($pdo)
    {        
        $this->pdo = $pdo;
    }

    /** 
    * build the DbManagement Object if connection to the database is ok
    *the database reference is define by the constants URL and 
    * @return \Croak\Iot\DatabasesDbManagement          the created DbManagement object
    * @throws DataBaseException     error in connecting to the database
    */
    public static function connect()
    {
        try{
            //open the database
            $pdo = new \PDO(DbManagement::URL);
            $ok = $pdo->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_NATURAL);
            $ok = $ok & $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $ok = $ok & $pdo->setAttribute(\PDO::ATTR_ORACLE_NULLS, \PDO::NULL_EMPTY_STRING);

            if(!$ok){
                throw new DataBaseException(DataBaseException::DB_ATTRIBUTE_FAILED);
            }

            return new DbManagement($pdo);
        }
        catch(\PDOException $e){
            throw new DataBaseException(DataBaseException::DB_CONNECTION_FAILED);
        }
    }

    /** 
    * prepares and execute a query given as a parameter
    * @param String $query          a query to access data in the database
    * @param array $arrayData       an array containing values to insert in the query
    * @throws Croak\Iot\Exceptions\DataBaseException    if an error occured when preparing or executing the query
    * @return the result of the query
    */
    public function query($query, $arrayData=array())  {

        try{
            $request = $this->pdo->prepare($query);
            $ok = $request->execute($arrayData);
            if(!$ok){
                throw new DataBaseException(DataBaseException::EXECUTE_FAILED);
            }
            return $request;
        }
        catch(\PDOException $e){
            throw new DataBaseException(DataBaseException::PREPARE_FAILED);
        }
    }

    /**
    * close the database connection
    */
    public function disconnect(){
      $this->pdo = NULL;
    }

}