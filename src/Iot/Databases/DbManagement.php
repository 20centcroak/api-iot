<?php

namespace Croak\Iot\Databases;
use Croak\Iot\Databases\Exceptions\DataBaseException;

class DbManagement
{
    private const URL = 'sqlite:../db/iotDB.sqlite';
    private $pdo;

    private function __construct($pdo)
    {
        //private constructor : building the object
        //should be done by calling connect()
        $this->pdo = $pdo;
    }

    public static function connect()
    {
        try{
            //open the database
            $pdo = new \PDO(DbManagement::URL);
            return new DbManagement($pdo);
        }
        catch(\PDOException $e){
            throw new DataBaseException();
        }
    }

    public function query($query, $arrayData=array())
    {    
        $request = $this->pdo->prepare($query);
        return $request->execute($arrayData);
    }

    public function disconnect()
    {
      // close the database connection
      $this->pdo = NULL;
    }

}