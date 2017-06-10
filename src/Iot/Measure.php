<?php

namespace Croak\Iot;
use Croak\Iot\Exceptions\MeasureException;

class Measure{

    const TYPE_KEY = "type";
    const UNIT_KEY = "unit";
    const VALUE_KEY = "value";
    const ID_DEVICE_KEY = "idDevice";
    const DATE_KEY = "created";

    private $type;
    private $unit;
    private $value;
    private $idDevice;
    private $date;

    private function __construct($data, $id){
        $this->date = date("Y-m-d H:i:s");
        $this->type = $data->{Measure::TYPE_KEY};
        $this->unit = $data->{Measure::UNIT_KEY}; 
        $this->value = $data->{Measure::VALUE_KEY}; 
        $this->idDevice = $id;
    }

    public static function create($json, $id){        
        $data = json_decode($json);

        if(!array_key_exists(Measure::TYPE_KEY, $data) || !array_key_exists(Measure::UNIT_KEY, $data) || !array_key_exists(Measure::VALUE_KEY, $data)){
            throw new MeasureException(MeasureException::MISSING_KEY);
        }
        if(empty($data->{Measure::TYPE_KEY}) || empty($data->{Measure::UNIT_KEY}) || empty($data->{Measure::VALUE_KEY})){
            throw new MeasureException(MeasureException::MISSING_VALUE);
        }

        return new Measure($data, $id);
    }

    public function getIdDevice(){
        return $this->idDevice;
    }

    public function getType(){
        return $this->type;
    }

    public function getValue(){
        return $this->value;
    }

    public function getUnit(){
        return $this->unit;
    }

    public function getDate(){
        return $this->date;
    }
}