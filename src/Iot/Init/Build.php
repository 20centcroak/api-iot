<?php

namespace Croak\Iot\Init;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Exceptions\DataBaseException;
use Croak\Iot\Exceptions\InitException;

class Build{

    const FILENAME = "../api-config.json";
    const INIT_KEY = "init";

    private function __construct(){}

    public static function isBuilt()
    {        
        if(Build::initFileOk()){
            return true;
        }

        try{
            $init = Build::createTables();
        }
        catch(InitException $e){
            return false;
        }

        return $init;
    }

    private static function initFileOk(){
        $json = file_get_contents(Build::FILENAME);
        if($json===false){
            return false;
        }

        $config = json_decode($json, true);
        if(!$config[INIT_KEY]===true){
            return false;
        }

        return true;
    }

    public static function createInitFile()
    {
        $array = array(Build::INIT_KEY => true);
        $json = json_encode($array);
        $file = fopen(Build::FILENAME, 'w');
        fwrite($file, $json);
        fclose($file);
        return Build::initFileOk();
    }

    public static function createTables()
    {
        try{
            $db = DbManagement::connect();
        }
        catch(DataBaseException $e){
            throw new InitException(InitException::INIT_DB_NOT_AVAILABLE);
        }

         $query="CREATE TABLE IF NOT EXISTS devices (
            id            INTEGER         PRIMARY KEY AUTOINCREMENT,
            sn            INTEGER,
            created       TEXT,
            id_user       INTEGER
        );";
        $errorDevices = $db->query($query);

        $query="CREATE TABLE IF NOT EXISTS users (
            id            INTEGER         PRIMARY KEY AUTOINCREMENT,
            firstname     TEXT,
            lastname      TEXT,
            email         TEXT            NOT NULL,
            keyword       TEXT            NOT NULL,
            birthdate     TEXT,
            weight        REAL,
            size          INTEGER,
            address       TEXT,
            phoneNumber   TEXT,
            comments      TEXT,
            created       TEXT
        );";
        $errorUsers = $db->query($query);

       $query="CREATE TABLE IF NOT EXISTS measures (
            id            INTEGER         PRIMARY KEY AUTOINCREMENT,
            id_device     INTEGER,
            type          TEXT,
            unit          TEXT,
            value         REAL,
            created       TEXT
        );";
        $errorMeasures = $db->query($query);

        if($errorDevices){
            throw new InitException(InitException::INIT_TABLE_DEVICE_FAILED);
        }
        if($errorUsers){
            throw new InitException(InitException::INIT_TABLE_USER_FAILED);
        }
        if($errorMeasures){
            throw new InitException(InitException::INIT_TABLE_MEASURE_FAILED);
        }

        return Build::createInitFile();
    }
}