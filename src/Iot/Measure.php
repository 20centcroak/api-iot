<?php

namespace Croak\Iot;
use Croak\Iot\Exceptions\MeasureException;

/**
 * Describes a Measure thanks to a set of parameters
 * a measure is described by its :
 * -type (temperature, pressure, ...)
 * -unit (°C, bar, ...)
 * -value
 * these 3 parameters should be present in the json string
 * representing the measure
 * the mesaure should be associated with a device defined by its serial number
 */
class Measure{

    /**
    *@var String    key names expected in json file
    */
    const TYPE_KEY = "type";
    const UNIT_KEY = "unit";
    const VALUE_KEY = "value";
    const ID_DEVICE_KEY = "idDevice";
    const DATE_KEY = "created";

    /**
    *@var String    type of measure
    */
    private $type;
    /**
    *@var String    unit of measure
    */
    private $unit;
    /**
    *@var number    value of measure
    */
    private $value;
    /**
    *@var String    serial number of assoicated device
    */
    private $snDevice;
    /**
    *@var date String    date of creation of measure
    */
    private $date;

    /** 
    * private constructor : building the object
    * should be done by calling create()
    * @param mixed $data        the decoded json string 
    * @param String $sn         the device sn associated with the measure
    */
    private function __construct($data, $sn){
        $this->date = date("Y-m-d H:i:s");
        $this->type = $data->{Measure::TYPE_KEY};
        $this->unit = $data->{Measure::UNIT_KEY}; 
        $this->value = $data->{Measure::VALUE_KEY}; 
        $this->snDevice = $sn;
    }

    /** 
    * build the Measure Object if the key/value of the json file are correct
    * @param Json $json             the json string containing measure parameters
    * @param String $sn             the serial number of the device
    * @return a new Measure Object
    * @throws MeasureException when a parameter for the measure object is missing in the json string
    */
    public static function create($json, $sn){        
        $data = json_decode($json);

        if(!array_key_exists(Measure::TYPE_KEY, $data) || !array_key_exists(Measure::UNIT_KEY, $data) || !array_key_exists(Measure::VALUE_KEY, $data)){
            throw new MeasureException(MeasureException::MISSING_KEY);
        }
        if(empty($data->{Measure::TYPE_KEY}) || empty($data->{Measure::UNIT_KEY}) || empty($data->{Measure::VALUE_KEY})){
            throw new MeasureException(MeasureException::MISSING_VALUE);
        }

        return new Measure($data, $sn);
    }

    /**
    * getter of snDevice parameter
    * @return String        device serial number
    */
    public function getIdDevice(){
        return $this->snDevice;
    }

    /**
    * getter of type parameter
    * @return String        measure type
    */
    public function getType(){
        return $this->type;
    }

    /**
    * getter of value parameter
    * @return Number        measure value
    */
    public function getValue(){
        return $this->value;
    }

    /**
    * getter of unit parameter
    * @return String        measure unit
    */
    public function getUnit(){
        return $this->unit;
    }

    /**
    * getter of date parameter
    * @return String        creation date of measure
    */
    public function getDate(){
        return $this->date;
    }
}