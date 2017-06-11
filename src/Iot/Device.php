<?php

namespace Croak\Iot;
use Croak\Iot\Exceptions\DeviceException;

class Device{

    const SN_KEY="sn";
    const NAME_KEY="name";
    private $sn;
    private $name;

    private function __construct($sn){
        $this->sn = $sn;
    }

    public static function create($sn){
        return new Device($sn);
    }

    public function update($json){
         $data = json_decode($json);

        if(!array_key_exists(Device::SN_KEY, $data)){
            throw new DeviceException(DeviceException::MISSING_KEY);
        }
        if(empty($data->{Device::SN_KEY})){
            throw new DeviceException(DeviceException::MISSING_VALUE);
        }

        $this->sn = $data->{Device::SN_KEY};
        $this->name = $data->{Device::NAME_KEY};
    }

    public function getSn(){
        return $this->sn;
    }

    public function getName(){
        return $this->name;
    }

}