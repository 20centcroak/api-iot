<?php

namespace Croak\Iot\Databases;

use Croak\Iot\Exception\DataBaseException;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Databases\IotQueries;
use Croak\Iot\Measure;

/**
 * Manages the table containing the measures
 */
class TableMeasures
{
    /**
     * add a measure to the measure table in the database
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @param Croak\Iot\Measure $measure the measure object containing all data
     * @throws DataBaseException     error in connecting to the database
     * @return id of the record
     */
    public function populate(DbManagement $db, Measure $measure)
    {
        $array = $measure->getValues();
        $db->query(SqliteQueries::ADD_MEASURE, $array);
        return $db->lastInsertId();
    }

    /**
     * get measures from the measure table in the database according to params
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @param Croak\Iot\Databases\IotQueries $queries the queries to select measures
     * @param array $params parameters for the query to indicate a type, a date ore something else
     * @throws DataBaseException     error in connecting to the database
     * @return array of Measures
     */
    public function getMeasures(DbManagement $db, IotQueries $queries, $params)
    {
        //parse params to check their validity and 
        //use the conventions to sort and filter results
        $parsed = $this->parseParams($params);

        //prepare query
        $query = $queries->selectMeasures();

        //if valid params have been detected
        //query is filled in with arguments
        if(isset($parsed)){
            foreach ($parsed as $type => $values) {
                switch($type){
                    case "min":
                        foreach ($values as $key => $val) {
                            $min = Measure::KEYS[$key]."-min";
                            $db->sortMin($query,Measure::KEYS[$key],$min);
                            $array[]=$val;
                        }
                        break;
                    case "max":
                        foreach ($values as $key => $val) {
                            $max = Measure::KEYS[$key]."-max";
                            $db->sortMax($query,Measure::KEYS[$key],$max);
                            $array[]=$val;
                        }
                        break;
                    case "up":
                        foreach ($values as $val) {
                            $db->orderUp($query,Measure::KEYS[$val]);
                        }
                        break;
                    case "down":
                        foreach ($values as $val) {
                            $db->orderDown($query,Measure::KEYS[$val]);
                        }
                        break;
                    case "equals":
                        foreach ($values as $key => $val) {
                            $db->sort($query,Measure::KEYS[$key], Measure::KEYS[$key]);
                            $array[]=$val;
                        }
                        break;
                }
            }
        }

        //close query
        $db->closeQuery($query);

        $answer = $db->query($query, $array);

        $measures = [];

        while ($row = $answer->fetch(\PDO::FETCH_ASSOC)) {
            foreach (MEASURE::KEYS as $key => $val) {
                $argsMeasure[$val]=$row[$val];
            }

            $measures[]=Measure::create($argsMeasure);
        }
        
        return $measures;
    }

    /**
    * parse parameters coming from the route. 
    * For example if route is /measures/type=temperature&value-min>24&value-up
    * then $params is a table like 
    * ["type"=>"temperature", "value-min"=>"24",  "value-up"=>""]
    * it should be parsed thanks to the specific syntax (-min, -max, -up, -down)
    * and each key/value of the table is checked thanks to the constants
    * KEYS and KEY_TYPES of the Measure Object
    * @param array $params the array of arguments
    * @return array parsed array with keys "min, max, up, down, equals" according
    * to the argument syntax. Each key addresses an array of value-type=>value
    */
    private function parseParams($params)
    {
        $array = array();

        //First we manage $equals, ie params like type=temperature
        //type is a key present in MEASURE::KEYS
        //and $params[type] is set with a non empty String
        //so this is a valid argument
        foreach($params as $key=>$val){
            $type = Measure::KEY_TYPES[$key];
            //check if the key is a key for the Measure object, if it is associated with a value and
            //if the value type is as expected by the Measure Object
            if (array_key_exists($key, Measure::KEYS) && strlen($val)>0 && $type($val)) {
                $array["equals"][$key]=$this->convertValue($val, $type);
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
                //check if the key is a key for the Measure object, if it is associated with a value and
                //if the value type is as expected by the Measure Object
                if (array_key_exists($key, Measure::KEYS) && 
                        strlen($params[$val])>0 &&
                        is_numeric($params[$val])
                    ){
                    $array[$name][$key] = $this->convertValue($params[$val], "is_numeric");
                }
            }
        }

        //we manage only $up and $down here
        $var_names=["up", "down"];
        foreach($var_names as $name){
            foreach($$name as $val){
                $key = substr($val,0,strlen($val)-strlen($name)-1);
                if (array_key_exists($key, Measure::KEYS)) {
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
            case "is_string":
                $value = strval($value);
                break;
            default:
                $value = strval($value);
        }

        return $value;
    }

    
}
