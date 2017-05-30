<?php

namespace Croak\Iot\Databases;
use Croak\Iot\Exception\DataBaseException;

class TableDevices
{
    private $_db;

    private function __construct($db)
    {
        //private constructor : building the object
        //should be done by calling connect()
        $this->_db = $db;
    }

    public function addDevice($sn)
    {
        //TODO vérifier qu'il n'existe pas déjà, on peut en principe
        //le laisser gérer par la bdd
        $request = $pdo->prepare("INSERT INTO DEVICES (sn, created) VALUES (:sntitre, :created)");
        $result = $stmt->execute(array(
                                        'sn'			=> $sn,
                                        'created'		=> date("Y-m-d H:i:s")
                                ));
    }

    public function associateUser($user)
    {
        DbUsers.connect();
    }

    public function disconnect()
    {
      // close the database connection
      $this->_db = NULL;
    }

}