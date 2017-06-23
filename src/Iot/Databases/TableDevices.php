<?php

namespace Croak\Iot\Databases;
use Croak\Iot\Exception\DataBaseException;
use Croak\Iot\Databases\SqliteQueries;

/**
 * Manages the table conatinaing the devices
 */
class TableDevices
{
    /**
    *@var Device    Device description
    */
    private $device;
    /**
    *@var String    serial number of device
    */
    private $sn;

    /**
     * construct the object thanks to the definition of a device object
     * @param Device $device        the device object defining a device
     */
    public function __construct($device)
    {
        $this->device = $device;
        $this->sn = $device->getSn();
    }

    /**
     * add a device to the device table in the database
     * @throws DataBaseException     error in connecting to the database
     */
    public function addDevice()
    {
        $db = DbManagement::connect();
        $array = array('sn'=> $this->sn,'created'=> date("Y-m-d H:i:s"));
        $queryOk = $db->query(SqliteQueries::ADD_DEVICE, $array);
        if($queryOk===false){
            throw new DataBaseException(DataBaseException::ADD_FAILED);
        }
        $db->disconnect();
    }

    /**
    * update device information from the database, using the device sn
    * @throws DataBaseException if database access failed
    */
    public function updateDeviceInformation()
    {
        $db = DbManagement::connect();
        $array = array('sn' => $this->sn);
        $answer = $db->query(SqliteQueries::GET_DEVICE_BY_SN, $array);

//TODO vérifier l'existence du device dans la base

        $db->disconnect();
        //TODO comprendre et changer cela
        $json = json_encode($answer[0]);
        $this->device->update($json);
    }

    /**
    * associate a user with the device
    * @param number $userId       the user id to associate with
    */
    public function associateUser($userId)
    {
        DbUsers.connect();
    }
}