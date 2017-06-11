<?php

namespace Croak\Iot\Databases;
use Croak\Iot\Exception\DataBaseException;
use Croak\Iot\Databases\SqliteQueries;

class TableDevices
{
    private $device;

    public function __construct($device)
    {
        $this->device = $device;
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

    public function deviceExists()
    {

    }

    public function updateDeviceInformation()
    {

    }

    public function associateUser($user)
    {
        DbUsers.connect();
    }
}