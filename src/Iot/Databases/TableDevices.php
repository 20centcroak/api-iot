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
    *@var Device
    */
    private $device;
    private $sn;

    /**
     * construct the object thanks to the definition of a device object
     * @param string $device        the device object defining a device
     */
    public function __construct($device)
    {
        $this->device = $device;
        $this->sn = $device->getSn();
    }

    /**
     * add a device to the device table in the database
     *
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

    public function updateDeviceInformation()
    {
        $db = DbManagement::connect();
        $array = array('sn' => $this->sn);
        $queryOk = $db->query(SqliteQueries::GET_DEVICE_BY_SN, $array);
        if($queryOk===false){
            throw new DataBaseException(DataBaseException::ADD_FAILED);
        }
        $db->disconnect();

        $json = json_encode($queryOk->fetchAll());
        $this->device->update($json);
    }

    public function associateUser($user)
    {
        DbUsers.connect();
    }
}