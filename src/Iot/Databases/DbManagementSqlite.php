<?php

namespace Croak\Iot\Databases;

use Croak\Iot\Exceptions\DataBaseException;

/**
 * Manages the database: connection, read/write
 * it uses PDO for a global management and an easy change to another database type
 * It makes this Object a generic type for database management
 */
class DbManagementSqLite implements DbManagement
{
    /**
    *@var PDO       object to address the database
    */
    private $pdo;

    /**
    * connect to the database
    * the database reference is defined by its URL
    * @param String $url url of the database
    * @return \Croak\Iot\Databases\DbManagementSqLite the created DbManagementSqLite object
    * @throws DataBaseException     error in connecting to the database
    */
    public function connect($url)
    {
        $url = "sqlite:".$url;
        try {
            //open the database
            $pdo = new \PDO($url);
            $ok = $pdo->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_NATURAL);
            $ok = $ok & $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $ok = $ok & $pdo->setAttribute(\PDO::ATTR_ORACLE_NULLS, \PDO::NULL_EMPTY_STRING);

            if (!$ok) {
                throw new DataBaseException(DataBaseException::DB_SETTINGS_FAILED);
            }

            $this->pdo = $pdo;
        } catch (\PDOException $e) {
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
    public function query($query, $arrayData = array())
    {

        try {
            $request = $this->pdo->prepare($query);
            $ok = $request->execute($arrayData);
            if (!$ok) {
                throw new DataBaseException(DataBaseException::QUERY_EXECUTION_FAILED);
            }
            return $request;
        } catch (\PDOException $e) {
            throw new DataBaseException(DataBaseException::QUERY_FAILED);
        }
    }

    /**
    * close the database connection
    */
    public function disconnect()
    {
        $this->pdo = null;
    }

    /**
    * returns the last inserted id
    * @return int
    */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
