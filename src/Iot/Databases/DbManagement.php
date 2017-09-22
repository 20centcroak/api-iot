<?php

namespace Croak\Iot\Databases;

/**
 * Interface to manage different kind of database
 */
interface DbManagement
{
    /** 
    * connect to database
    * @param String $url url of the database
    * @throws DataBaseException     error in connecting to the database
    */
    public function connect($url);

    /** 
    * prepares and execute a query given as a parameter
    * @param String $query          a query to access data in the database
    * @param array $data       an array containing values to insert in the query
    * @return the result of the query
    * @throws DataBaseException     error with the query
    */
    public function query($query, $data); 

    /**
    * close the database connection
    */
    public function disconnect();

    /**
    * returns the last inserted id
    * @return int
    */
    public function lastInsertId();

}