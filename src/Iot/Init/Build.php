<?php

namespace Croak\Iot\Init;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Exceptions\DataBaseException;
use Croak\Iot\Exceptions\InitException;

class Build{

    const FILENAME = "../api-config.json";
    const INIT_KEY = "init";

    private function __construct(){}

    public static function check()
    {        
        if(Build::initFileOk()){
            return true;
        }

        try{
            $init = Build::createTables();
            return $init;
        }
        catch(DataBaseException $e){
            throw new InitException(InitException::INIT_DB_NOT_AVAILABLE);
        }       
    }

    private static function initFileOk(){
        $json = file_get_contents(Build::FILENAME);
        if($json===false){
            return false;
        }

        $config = json_decode($json, true);
        if(!$config[Build::INIT_KEY]===true){
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
        $db = DbManagement::connect();

        $query="CREATE TABLE IF NOT EXISTS devices (
            id            INTEGER         PRIMARY KEY AUTOINCREMENT,
            sn            TEXT,
            created       TEXT,
            id_user       INTEGER
        );";
        $devicesCreated = $db->query($query);

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
        $usersCreated = $db->query($query);

       $query="CREATE TABLE IF NOT EXISTS measures (
            id            INTEGER         PRIMARY KEY AUTOINCREMENT,
            id_device     TEXT,
            type          TEXT,
            unit          TEXT,
            value         REAL,
            created       TEXT
        );";
        $measuresCreated = $db->query($query);

        $db->disconnect();

        if($devicesCreated===false){
            throw new InitException(InitException::INIT_TABLE_DEVICE_FAILED);
        }
        if($usersCreated===false){
            throw new InitException(InitException::INIT_TABLE_USER_FAILED);
        }
        if($measuresCreated===false){
            throw new InitException(InitException::INIT_TABLE_MEASURE_FAILED);
        }

        return Build::createInitFile();
    }
}