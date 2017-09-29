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
     * add a device to the device table in the database
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @param Devide $device the device Object
     * @throws DataBaseException     error in connecting to the database
     */
    public function populate(DbManagement $db, Device $device)
    {
        $array = $device->getValues();
        $db->query(SqliteQueries::ADD_DEVICE, $array);
        return $db->lastInsertId();
    }

    /**
    * update device information from the database, using the device sn
    * @param Croak\Iot\Databases\DbManagement $db the database connector
    * @throws DataBaseException if database access failed
    * @return true if the device exists in database and the device information has been updated, false othewise    *
    */
    public function getDevices(DbManagement $db, $params)
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
