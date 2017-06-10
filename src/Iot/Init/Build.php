<?php

namespace Croak\Iot\Init;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Exceptions\DataBaseException;
use Croak\Iot\Databases\SqliteQueries;
use Croak\Iot\Init\Config;

class Build{

    private function __construct(){}

//TODO lire le contenu du fichier et le mettre dans un tableau, ne modifier que les paramètres que l'on souhaite et 
//réécrire dans le fichier l'ensemble du tableau avec les modifs. Il faut donc différentier un init false d'un fichier inexistant


    private static function createInitFile($config)
    {
        $config = Config::setDefault();
        $default_json = $config->getJson();
        $file = fopen(Config::FILENAME, 'w');
        if($file===false){
            throw new BuildException(BuildException::CONFIG_FILE_CREATION_FAILED);
        }
        $bytes = fwrite($file, $default_json);
        if($bytes===false){
            throw new BuildException(BuildException::CONFIG_FILE_CREATION_FAILED);
        }
        fclose($file);
        return true;
    }

    public static function build()
    {
        try{
            $db = DbManagement::connect();
        }
        catch (DataBaseException $e){
            throw new BuildException(BuildException::INIT_DB_NOT_AVAILABLE)
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

        return true;
    }
}