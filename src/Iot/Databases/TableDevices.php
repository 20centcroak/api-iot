<?php

namespace Croak\Iot\Databases;

use Croak\Iot\Databases\SqliteQueries;
use Croak\IoT\Device;
use Croak\Iot\Databases\DbManagement;

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
     * construct the object thanks to the definition of a device object
     * @param Device $device        the device object defining a device
     */
    public function __construct(Device $device)
    {
        $this->device = $device;
    }

    /**
     * add a device to the device table in the database
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @param $name [optional] name of the device
     * @throws DataBaseException     error in connecting to the database
     */
    public function addDevice(DbManagement $db, $name = "")
    {
        $array = array(
            Device::NAME_KEY=>$this->device->getName(),
            Device::SN_KEY=> $this->device->getSn(),
            Device::CREATED_KEY=> date("Y-m-d H:i:s")
        );

        $db->query(SqliteQueries::ADD_DEVICE, $array);
        $this->updateDeviceInformation($db);
    }

    /**
    * update device information from the database, using the device sn
    * @param Croak\Iot\Databases\DbManagement $db the database connector
    * @throws DataBaseException if database access failed
    * @return true if the device exists in database and the device information has been updated, false othewise    *
    */
    public function updateDeviceInformation(DbManagement $db)
    {        
        $array = array(Device::SN_KEY => $this->device->getSn());

        $answer = $db->query(SqliteQueries::GET_DEVICE_BY_SN, $array);
        
        $devices = [];
        while ($row = $answer->fetch(\PDO::FETCH_ASSOC)) {
            $devices[] = [
                Device::NAME_KEY => $row[Device::NAME_KEY],
                Device::SN_KEY => $row[Device::SN_KEY],
                Device::CREATED_KEY => $row[Device::CREATED_KEY]
            ];
        }
        
        if (count($devices) >=1) {
            $json = json_encode($devices[0]);
        } else {
            return false;
        }

        $this->device->update($json);
        return true;
    }

    /**
    * associate a user with the device
    * @param number $userId       the user id to associate with
    */
    public function associateUser($userId)
    {
        #TODO
    }
}
