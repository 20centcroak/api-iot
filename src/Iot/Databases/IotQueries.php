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
    * measures selection
    * @return String query to select the measures
    */
    public function selectMeasures();

    /**
    * users selection
    * @return String query to select the users
    */
    public function selectUsers();
}