<?php

namespace Croak\Iot;
use Croak\Iot\Exceptions\MeasureException;
use Croak\Iot\Databases\DbManagement;

class Measure{

    const TYPE_KEY = "type";
    const UNIT_KEY = "unit";
    const VALUE_KEY = "value";
    const ID_DEVICE_KEY = "id_device";
    const DATE_KEY = "created";

    private $type;
    private $unit;
    private $value;
    private $id_device;
    private $date;

    private function __construct($data, $id){
        print("\ntransferred array =");
        print(var_dump($data));
        $this->date = date("Y-m-d H:i:s");
        $this->type = $data->{Measure::TYPE_KEY};
        $this->unit = $data->{Measure::UNIT_KEY}; 
        $this->value = $data->{Measure::VALUE_KEY}; 
        $this->id_device = $id;
        print("\nvalue=".$this->value);
    }

    public static function create($json, $id){        
        $data = json_decode($json);

        //print("\nvalue=".$data["value"]);
        // if(!array_key_exists(TYPE_KEY) && !array_key_exists(UNIT_KEY) && !array_key_exists(VALUE_KEY)){
        //     throw new MeasureException(MeasureException::MISSING_KEY);
        // }
        // if(!empty($data[TYPE_KEY]) && !empty($data[UNIT_KEY]) && !empty($data[VALUE_KEY])){
        //     throw new MeasureException(MeasureException::MISSING_VALUE);
        // }

        return new Measure($data, $id);
    }

    public function populate(){
        $query = "INSERT INTO measures (id_device, type, unit, value, created) VALUES (:id_device, :type, :unit, :value, :created)";
        $array = array(
            Measure::ID_DEVICE_KEY		=> $this->id_device,
            Measure::TYPE_KEY			=> $this->type,
            Measure::UNIT_KEY           => $this->unit,
            Measure::VALUE_KEY			=> $this->value,
            Measure::DATE_KEY		    => $this->date
        );

        try{
            $db = DbManagement::connect();
            $queryOk = $db->query($query, $array);
            if($queryOk===false){
                throw new MeasureException(MeasureException::ADD_FAILED);
            }            
        }
        catch(DataBaseException $e){
            throw new MeasureException(MeasureException::DB_CONNECTION_FAILED);
        }
        $db->disconnect();
    }

}