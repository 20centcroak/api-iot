<?php

namespace Croak\Iot;

class Device{

    private $sn;

    public function __construct($sn){
        $this->sn = $sn;
    }

    public function getID(){
        return $this->sn;
    }

}