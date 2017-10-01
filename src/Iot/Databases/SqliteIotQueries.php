<?php

namespace Croak\Iot\Databases;

use Croak\Iot\Measure;
use Croak\Iot\Device;
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
    const GET_DEVICES = "SELECT * FROM ".IotQueries::MEASURES_TABLE_NAME;
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
        $query = $this->createTable("measures");
        $unique = ", UNIQUE(id";
        foreach(Measure::KEYS as $key=>$val){
            $query = $query.", $val ";
            $query = $query.$this->translateType(Measure::KEY_TYPES[$key]);
            if(Measure::KEY_REQUIRED[$key]){
                $query = $query." NOT NULL";
            }
            if(Measure::KEY_UNIQUE[$key]){
                $unique = $unique.", $val";
            }
        }
        $unique = $unique.")";
        $query = $query.$unique;
        $query = $query.");";
        return $query;
    }

    /**
    * Device table creation
    * @return String query to create the table
    */
    public function createDeviceTable(){
        $query = $this->createTable("devices");
        $unique = ", UNIQUE(id";
        foreach(Device::KEYS as $key=>$val){
            $query = $query.", $val ";
            $query = $query.$this->translateType(Device::KEY_TYPES[$key]);
            if(Device::KEY_REQUIRED[$key]){
                $query = $query." NOT NULL";
            }
            if(Device::KEY_UNIQUE[$key]){
                $unique = $unique.", $val";
            }
        }
        $unique = $unique.")";
        $query = $query.$unique;
        $query = $query.");";
        return $query;
    }

    /**
    * User table creation
    * @return String query to create the table
    */
    public function createUserTable(){
        $query = $this->createTable("users");
        #TODO à poursuivre avec la création d'un objet User
        return $query;
    }
    
    /**
    * devices selection
    * @return String query to select the devices
    */
    public function selectDevices(){
        return SqliteIotQueries::GET_DEVICES;
    }

    /**
    * measures selection
    * @return String query to select the measures
    */
    public function selectMeasures(){
        return SqliteIotQueries::GET_MEASURES;
    }

    /**
    * users selection
    * @return String query to select the users
    */
    public function selectUsers(){
        return SqliteIotQueries::GET_USERS;
    }

    /**
    * all tables are created with the same syntax. 
    * @param $name the table name to create
    * @return String the corresponding query
    */
    private function createTable($name){
        return SqliteIotQueries::CREATE_TABLE.$name." (id    INTEGER    PRIMARY KEY AUTOINCREMENT";
     }
    
    /**
    * according to the expected data type, different word are used for the database fields 
    * @param $type the expected data type
    * @return the query word corresponding to the data type to describe the database field
    */
    private function translateType($type){
         switch($type){
             case "is_string":
                 return "TEXT";
                 break;
             case "is_numeric":
                 return "REAL";
                 break;
         }
     }
}