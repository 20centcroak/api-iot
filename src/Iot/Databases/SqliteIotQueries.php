<?php

namespace Croak\Iot\Databases;

use Croak\Iot\Measure;
use Croak\Iot\Device;
use Croak\Iot\User;
use Croak\Iot\Databases\IotQueries;

/**
* a set of constants defining all the sqlite queries of the app
*/
class SqliteIotQueries implements IotQueries
{
     /**
    *@var String    basic sqlite queries used in the app
    */
    const CREATE_TABLE = "CREATE TABLE IF NOT EXISTS "; 
    const GET_DEVICES = "SELECT * FROM ".IotQueries::DEVICES_TABLE_NAME;
    const ADD_DEVICE = "INSERT OR IGNORE INTO ".IotQueries::DEVICES_TABLE_NAME;
    const GET_MEASURES = "SELECT * FROM ".IotQueries::MEASURES_TABLE_NAME;
    const ADD_MEASURE = "INSERT INTO ".IotQueries::MEASURES_TABLE_NAME;
    const GET_USERS = "SELECT * FROM ".IotQueries::USERS_TABLE_NAME;
    const ADD_USER = "INSERT OR IGNORE INTO ".IotQueries::USERS_TABLE_NAME;

    /**
    * Measure table creation
    * @return String query to create the table
    */
    public function createMeasureTable(){
        return $this->createTable("measures", 
                                    Measure::KEYS, 
                                    Measure::KEY_TYPES, 
                                    Measure::KEY_REQUIRED, 
                                    Measure::KEY_UNIQUE
                                );
    }

    /**
    * Device table creation
    * @return String query to create the table
    */
    public function createDeviceTable(){
        return $this->createTable("devices", 
                                    Device::KEYS, 
                                    Device::KEY_TYPES, 
                                    Device::KEY_REQUIRED, 
                                    Device::KEY_UNIQUE
                                );       
    }

    /**
    * User table creation
    * @return String query to create the table
    */
    public function createUserTable(){
        return $this->createTable("users", 
                                        User::KEYS, 
                                        User::KEY_TYPES, 
                                        User::KEY_REQUIRED, 
                                        User::KEY_UNIQUE
    );       
}
    
    /**
    * devices selection
    * @return String query to select the devices
    */
    public function selectDevices(){
        return SqliteIotQueries::GET_DEVICES;
    }

    /**
    * adding a device in database
    * @return boolean true if device has been added
    */
    public function addDevice(){
        return SqliteIotQueries::ADD_DEVICE;
    }

    /**
    * measures selection
    * @return String query to select the measures
    */
    public function selectMeasures(){
        return SqliteIotQueries::GET_MEASURES;
    }

    /**
    * adding a measure in database
    * @return boolean true if measure has been added
    */
    public function addMeasure(){
        return SqliteIotQueries::ADD_MEASURE;
    }

    /**
    * users selection
    * @return String query to select the users
    */
    public function selectUsers(){
        return SqliteIotQueries::GET_USERS;
    }

     /**
    * adding a user in database
    * @return boolean true if user has been added
    */
    public function addUser(){
        return SqliteIotQueries::ADD_USER;
    }

    /**
    * all tables are created with the same syntax. 
    * @param $name the table name to create
    * @return String the corresponding query
    */
    private function createTable($name, $keys, $keyTypes, $keyRequired, $keyUnique){
        $query = SqliteIotQueries::CREATE_TABLE.$name." (id    INTEGER    UNIQUE   PRIMARY KEY AUTOINCREMENT";

        foreach($keys as $key=>$val){
            $query = $query.", $val ";
            $query = $query.$this->translateTypeForTable($keyTypes[$key]);
            if($keyRequired[$key]){
                $query = $query." NOT NULL";
            }
            if($keyUnique[$key]){
                $query = $query." UNIQUE";
            }
        }

        $query = $query.");";
        return $query;
     }
    
    /**
    * according to the expected data type, different word are used for the database fields 
    * @param $type the expected data type
    * @return the query word corresponding to the data type to describe the database field
    */
    private function translateTypeForTable($type){
         switch($type){
             case "is_string":
                 return "TEXT";
                 break;
             case "is_numeric":
                 return "REAL";
                 break;
            case "is_int":
                 return "INTEGER";
                 break;
         }
     }
}