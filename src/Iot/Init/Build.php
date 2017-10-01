<?php

namespace Croak\Iot\Init;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Exceptions\DataBaseException;
use Croak\Iot\Databases\IotQueries;
use Croak\Iot\Exceptions\BuildException;
use Croak\Iot\Init\Config;

/**
 * Build the application during the first launch
 * it creates necessary folders and files 
 * it creates database and tables
 * it defines a default set of parameters in the api-config.json files if not defined
 * all functions should be accessed statically 
 */
class Build{

    /**
    *private constructor: all functions should be accessed statically
    */
    private function __construct(){}

    /**
    * define a default set of parameters and save the config file
    * @param Config $config     a config Object defining the app configuration
    * @return boolean           true if the init is successfull
    * @throws InitException     when the config file can't be created or overwritten
    */
    public static function createInitFile($config)
    {
        $config->setDefault();
        $config->updateFile();

        return true;
    }

    /**
    * create the database directory, the database and its associated tables
    * when everything goes right, it set the "init" flage of the configuration file to true
    * @param Config $config     a Config Object containing configuration parameters
    * @param Config $config     a Config Object containing configuration parameters
    * @return boolean           true if build is successfull
    * @throws BuildException    when the database or the tables can't be created or accessed
    */
    public static function build(Config $config, DbManagement $db, IotQueries $queries)
    {
        try{
            $db->connect($config->getDbUrl());
        }
        catch (DataBaseException $e){
            throw new BuildException(DataBaseException::DB_CONNECTION_FAILED);
        }

        $query = $queries->createMeasureTable();


        $measuresCreated = $db->query($query);
        // $query = $queries->createDeviceTable();
        // $devicesCreated = $db->query($query);
        // $query = $queries->createUserTable();
        // $usersCreated = $db->query($query);

        $db->disconnect();

        // if($devicesCreated===false){
        //     throw new BuildException(BuildException::INIT_TABLE_DEVICE_FAILED);
        // }
        // if($usersCreated===false){
        //     throw new BuildException(BuildException::INIT_TABLE_USER_FAILED);
        // }
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