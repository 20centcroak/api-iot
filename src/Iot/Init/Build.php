<?php

namespace Croak\Iot\Init;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Databases\Exceptions\DataBaseException;
use Croak\Iot\Databases\SqliteQueries;
use Croak\Iot\Exceptions\BuildException;
use Croak\Iot\Init\Config;

class Build{

    private function __construct(){}

    public static function createInitFile($config)
    {
        $config->setDefault();
        try{
            $config->updateFile();
        }
        catch(InitException $e){
            throw new BuildException(BuildException::CONFIG_FILE_CREATION_FAILED);
        }
        return true;
    }

    public static function build($config)
    {
        if(!file_exists(DbManagement::DB_FOLDER)){
            if(!mkdir(DbManagement::DB_FOLDER)){
                throw new BuildException(BuildException::CREATE_DB_DIRECTORY_FAILED);
            }
        }

        try{
            $db = DbManagement::connect();
        }
        catch (DataBaseException $e){
            throw new BuildException(DataBaseException::DB_CONNECTION_FAILED);
        }

        $devicesCreated = $db->query(SqliteQueries::CREATE_TABLE_DEVICES);
        $usersCreated = $db->query(SqliteQueries::CREATE_TABLE_USERS);
        $measuresCreated = $db->query(SqliteQueries::CREATE_TABLE_MEASURES);

        $db->disconnect();

        if($devicesCreated===false){
            throw new BuildException(BuildException::INIT_TABLE_DEVICE_FAILED);
        }
        if($usersCreated===false){
            throw new BuildException(BuildException::INIT_TABLE_USER_FAILED);
        }
        if($measuresCreated===false){
            throw new BuildException(BuildException::INIT_TABLE_MEASURE_FAILED);
        }

        $config->setInit(true);
        try{
            $config->updateFile();
        }
        catch(InitException $e){
            throw new BuildException(BuildException::CONFIG_FILE_CREATION_FAILED);
        }

        return true;
    }
}