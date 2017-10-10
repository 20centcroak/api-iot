<?php

namespace Croak\Iot\Databases;

use Croak\Iot\Exception\DataBaseException;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\IotObject;

/**
 * Manages an Iot Table by providing a new IotObject or by quering data 
 *associated with an IotObject in the database
 */
class IotTable
{
    /**
     * add an Object to the table in the database
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @param Croak\Iot\IotObject $object the IotObject containing all needed data to populate database
     * @param String $query the query to populate database
     * @throws DataBaseException     error in connecting to the database
     * @return id of the record
     */
    public function populate(DbManagement $db, IotObject $object, $query)
    {
        $keys = array_values($iotObject->getKeys());
        $db->add($query, $keys);
        $array = $object->getValues();
        $db->query($query, $array);
        return $db->lastInsertId();
    }

    /**
     * get data from the table in the database according to the params and the expected IotObject
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @param String $query the query to select data in database
     * @param array $params parameters for the query to indicate filters or sorting
     * @param Croak\Iot\IotObject $iotObject 
     * @throws DataBaseException     error in connecting to the database
     * @return mixed array containing key/value pair defininf an IotObject
     */
    public function getData(DbManagement $db, $query, $params, IotObject $iotObject)
    {       
        $keys = $iotObject->getKeys();

        //parse params to check their validity and 
        //use the conventions to sort and filter results
        $parsed = $this->parseParams($params, $keys, $iotObject->getTypes());
        
        $array=[];

        //if valid params have been detected
        //query is filled in with arguments
        if(isset($parsed)){
            foreach ($parsed as $type => $values) {
                switch($type){
                    case "min":
                        foreach ($values as $key => $val) {
                            $min = $keys[$key]."-min";
                            $db->sortMin($query,$keys[$key],$min);
                            $array[]=$val;
                        }
                        break;
                    case "max":
                        foreach ($values as $key => $val) {
                            $max = $keys[$key]."-max";
                            $db->sortMax($query,$keys[$key],$max);
                            $array[]=$val;
                        }
                        break;
                    case "up":
                        foreach ($values as $val) {
                            $db->orderUp($query,$keys[$val]);
                        }
                        break;
                    case "down":
                        foreach ($values as $val) {
                            $db->orderDown($query,$keys[$val]);
                        }
                        break;
                    case "equals":
                        foreach ($values as $key => $val) {
                            $db->sort($query,$keys[$key], $keys[$key]);
                            $array[]=$val;
                        }
                        break;
                }
            }
        }

        //close query
        $db->closeQuery($query);

        $answer = $db->query($query, $array);

        $args=[];
        while ($row = $answer->fetch(\PDO::FETCH_ASSOC)) {
            foreach ($keys as $key => $val) {
                $argsIotObject[$val]=$row[$val];
            }
            $args[] = $argsIotObject;
        }

        return $args;
    }

    /**
    * parse parameters coming from the route. 
    * For example if route is /measures/type=temperature&value-min>24&value-up
    * then $params is a table like 
    * ["type"=>"temperature", "value-min"=>"24",  "value-up"=>""]
    * it should be parsed thanks to the specific syntax (-min, -max, -up, -down)
    * and each key/value of the table is checked thanks to the expected types
    * @param array $params the array of arguments
    * @param array $types the array of expected types. Params and types should have the same keys
    * @return array parsed array with keys "min, max, up, down, equals" according
    * to the argument syntax. Each key addresses an array of value-type=>value
    */
    private function parseParams($params, $keys, $types)
    {
        $array = array();

        //First we manage $equals, ie params like type=temperature
        //type is a key present in $keys
        //and $params[type] is set with a non empty String
        //so this is a valid argument
        foreach($params as $key=>$val){
            //check if the key is a key for the object, if it is associated with a value
            if (array_key_exists($key, $keys) && strlen($val)>0) {
                $array["equals"][$key]=$this->convertValue($val, $types[$key]);;
            }
        }

        //for a route like measures/1234?value-min=12&type-up&type=temperature
        //$params is like ["value-min"=>"12", "type"=>"temperature", "type-up"=>"", "deviceSn"=>"1234"]
        //$paramKeys is like ["0"=>"value-min", "1"=>"type", "2"=>"type-up", "3"=>"deviceSn"]
        $paramKeys = array_keys($params);

        $var_names=["min", "max", "up", "down"];
        //for min, max, up and down
        foreach ($var_names as $name) {
            //looking for "-XXX" (where XXX = min, max, up or down)
            $regex = "/\-".$name."/i";

            //$min, $max, $up or $down are arrays containing only String like
            //value-min for $min
            //value-max for $max
            //value-up for $up
            //value-down for $down
            $$name = preg_grep($regex, $paramKeys);            
        }

        //we manage only $min and $max here
        $var_names=["min", "max"];
        foreach($var_names as $name){
            //we look at tables $min and $max
            foreach($$name as $val){
                //from a String like value-min, we extract $key=value
                $key = substr($val,0,strlen($val)-strlen($name)-1);
                //check if the key is a key for the object, if it is associated with a value and
                //if the value type is as expected by the Object
                if (array_key_exists($key, $keys) && 
                        strlen($params[$val])>0 &&
                        is_numeric($params[$val])
                    ){
                    $array[$name][$key] = $this->convertValue($params[$val], $types[$key]);
                }
            }
        }

        //we manage only $up and $down here
        $var_names=["up", "down"];
        foreach($var_names as $name){
            foreach($$name as $val){
                $key = substr($val,0,strlen($val)-strlen($name)-1);
                if (array_key_exists($key, $keys)) {
                    $array[$name][] = $key;
                }
            }
        }

        return $array;        
    }

    /**
    * values are converted thanks to the given type
    * @param mixed $value the value to convert
    * @param String $type the function name for data type testing: "is_numeric" or "is_string"
    * @return the converted value
    */
    private function convertValue($value, $type){

        switch($type){
            case "is_numeric":
                $value = floatval($value);
                break;
            case "is_float":
                $value = floatval($value);
                break;
            case "is_string":
                $value = strval($value);
                break;
            default:
                $value = strval($value);
        }

        return $value;
    }

    
}
