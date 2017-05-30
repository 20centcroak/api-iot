<?php

namespace Croak\Iot;
use Croak\Iot\Databases\DbDevices;

class Device{

    private $sn;

    public function __construct($sn){
        $this->sn = $sn;
    }

    public function registerDevice(){
        $dbDevices = DbDevices::connect();
        $dbDevices->addDevice($sn);
    }

    public function getID(){

    }

}