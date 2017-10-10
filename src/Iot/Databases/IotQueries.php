<?php

namespace Croak\Iot\Databases;

/**
 * Interface to manage different kind of database
 */
interface IotQueries
{
    /**
    *@var String  constants to name the tables
    */
    const MEASURES_TABLE_NAME = "measures";
    const DEVICES_TABLE_NAME = "devices";
    const USERS_TABLE_NAME = "users";

    /**
    * Measure table creation
    * @return String query to create the table
    */
    public function createMeasureTable();

    /**
    * Device table creation
    * @return String query to create the table
    */
    public function createDeviceTable();

    /**
    * User table creation
    * @return String query to create the table
    */
    public function createUserTable();

    /**
    * devices selection
    * @return String query to select the devices
    */
    public function selectDevices();

    /**
    * adding a device in database
    * @return boolean true if device has been added
    */
    public function addDevice();

    /**
    * measures selection
    * @return String query to select the measures
    */
    public function selectMeasures();

    /**
    * adding a measure in database
    * @return boolean true if measure has been added
    */
    public function addMeasure();

    /**
    * users selection
    * @return String query to select the users
    */
    public function selectUsers();
}