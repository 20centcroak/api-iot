<?php

namespace Croak\Iot;

use \Croak\DbManagement\DbManagement;
use \Croak\DbManagement\DbManagementTable;
use Croak\Iot\Databases\IotQueries;
use Croak\Iot\Device;
use Croak\Iot\Measure;

/**
 * Manages the requests addressed by the routes. 
 */
class IoTRequests{

    const POST_TYPES=[
        "measure"=>"postMeasure",
        "device"=>"postDevice",
        "user"=>"postUser"
    ];

    const GET_TYPES=[
        "measure"=>"getMeasures",
        "device"=>"getDevices",
        "user"=>"getUsers"
    ];

    /**
     * Manages a new entry
     *
     * @param Croak\DbManagement\DbManagement $db the database connector
     * @param Croak\Iot\Databases  IotQueries $type     the queries to be used with the database
     * @param params $params                            a params object containing the measure parameters
     * @param String $type                              one of the type listed in the const POST_TYPES
     * @throws IotException        the required fields were not found in the $params arrays
     * @throws DataBaseException    Error while connecting to the database
     */
    public static function post(DbManagement $db, IotQueries $queries, $params, $type){
        $table = new DbManagementTable();
        $function = IoTRequests::POST_TYPES[$type];

        return IoTRequests::$function($table, $db, $queries, $params);   
    }

    /**
     * Manages a new entry for measure associated with a given device
     *
     * @param Croak\DbManagement\DbManagementTable $table       the iotTable object to use for quering
     * @param Croak\DbManagement\DbManagement $db      the database connector
     * @param Croak\Iot\Databases  IotQueries $type     the queries to be used with the database
     * @param params $params                            a params object containing the measure parameters
     * @throws IotException        the required fields were not found in the $params arrays
     * @throws DataBaseException    Error while connecting to the database
     */
    public static function postMeasure(DbManagementTable $table, DbManagement $db, IotQueries $queries, $params){

        #TODO il faudra modifier cela : le device doit être créé indépendamment et on devra vérifier 
        #TODO qu'il existe avant d'ajouter une mesure
        #TODO on vérifiera en même temps si son sn respecte le pattern
        $deviceParams[Device::KEYS["deviceSn"]] = $params[Measure::KEYS["deviceSn"]];
        $deviceParams[Device::KEYS["date"]] = $params[Measure::KEYS["date"]];
        $table->populate($db,new Device($deviceParams), $queries->addDevice());

        $id = $table->populate($db, new Measure($params), $queries->addMeasure());

        $db->disconnect();
        return $id;
    }

    /**
     * Manages a new entry for user associated with a given device
     *
     * @param Croak\DbManagement\DbManagementTable $table       the iotTable object to use for quering
     * @param Croak\DbManagement\DbManagement $db      the database connector
     * @param Croak\Iot\Databases  IotQueries $type     the queries to be used with the database
     * @param params $params                            a params object containing the measure parameters
     * @throws IotException        the required fields were not found in the $params arrays
     * @throws DataBaseException    Error while connecting to the database
     */
    public static function postUser(DbManagementTable $table, DbManagement $db, IotQueries $queries, $params){
        $id = $table->populate($db,  new User($params), $queries->addUser());        
        $db->disconnect();
        return $id;
    }

    /**
     * Manages a new entry for device associated with a given device
     *
     * @param Croak\DbManagement\DbManagementTable $table       the iotTable object to use for quering
     * @param Croak\DbManagement\DbManagement $db      the database connector
     * @param Croak\Iot\Databases  IotQueries $type     the queries to be used with the database
     * @param params $params                            a params object containing the measure parameters
     * @throws IotException        the required fields were not found in the $params arrays
     * @throws DataBaseException    Error while connecting to the database
     */
    public static function postDevice(DbManagementTable $table, DbManagement $db, IotQueries $queries, $params){
        $id = $table->populate($db,  new Device($params), $queries->addDevice());        
        $db->disconnect();
        return $id;
    }


    /**
     * Manages request for delivering iotObject
     *
     * @param Croak\DbManagement\DbManagement $db the database connector
     * @param Croak\Iot\Databases  IotQueries $type     the queries to be used with the database
     * @param params $params                            a params object containing the measure parameters
     * @param String $type                              one of the type listed in the const POST_TYPES
     * @throws DataBaseException    Error while connecting to the database
     */
     public static function get(DbManagement $db, IotQueries $queries, $params, $type){
        
            $table = new DbManagementTable();
            $function = IoTRequests::GET_TYPES[$type];

            return IoTRequests::$function($table, $db, $queries, $params);            
    }

    /**
     * Manages a request for delivering measures
     *
     * @param Croak\DbManagement\DbManagementTable $table       the iotTable object to use for quering
     * @param Croak\DbManagement\DbManagement $db      the database connector
     * @param Croak\Iot\Databases  IotQueries $type     the queries to be used with the database
     * @param params $params                            a params object containing the measure parameters
     * @throws DataBaseException    Error while connecting to the database
     */
     private static function getMeasures(DbManagementTable $table, DbManagement $db, IotQueries $queries, $params){

        $argMeasures = $table->getData($db, $queries->selectMeasures(), $params, new Measure(null));        
        
        $measures = [];
        foreach($argMeasures as $val){
            $measures[]=new Measure($val);
        }

        return $measures;
      }

    /**
     * Manages a request for information on device
     * if the device does not exist, a IotException occurs
     *
     * @param Croak\DbManagement\DbManagementTable $table       the iotTable object to use for quering
     * @param Croak\DbManagement\DbManagement $db      the database connector
     * @param Croak\Iot\Databases  IotQueries $type     the queries to be used with the database
     * @param params $params                            a params object containing the measure parameters
     * @throws DataBaseException    Error while connecting to the database
     */
     private static function getDevices(DbManagementTable $table, DbManagement $db, IotQueries $queries, $params){

        $argDevices = $table->getData($db, $queries->selectDevices(), $params, new Device(null));

        $devices = [];
        foreach($argDevices as $val){
            $dev=new Device($val);
            $devices[] = $dev;
        }

        return $devices;       
    }

    /**
     * Manages a request for information on user
     * if the user does not exist, a IotException occurs
     *
     * @param Croak\DbManagement\DbManagementTable $table       the iotTable object to use for quering
     * @param Croak\DbManagement\DbManagement $db      the database connector
     * @param Croak\Iot\Databases  IotQueries $type     the queries to be used with the database
     * @param params $params                            a params object containing the measure parameters
     * @throws DataBaseException    Error while connecting to the database
     */
    private static function getUsers(DbManagementTable $table, DbManagement $db, IotQueries $queries, $params){
        
                $argUsers = $table->getData($db, $queries->selectUsers(), $params, new User(null));
        
                $users = [];
                foreach($argUsers as $val){
                    $user=new User($val);
                    $users[] = $user;
                }
        
                return $users;       
            }

}