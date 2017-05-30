<?php

namespace Croak\Iot\Exceptions;

class InitException extends \Exception
{
    const INIT_FILE_FAILED = 0;
    const INIT_TABLE_DEVICE_FAILED = 1;
    const INIT_TABLE_USER_FAILED = 2;
    const INIT_TABLE_MEASURE_FAILED = 3;
    const INIT_DB_NOT_AVAILABLE = 4;

    protected $message = 'api initialisation has failed';
    private $errorCode;

    public function __construct($code)
    {
        parent::__construct($this->message);
        $this->errorCode = $code;
    }

    public function getErrorCode(){
        return $this->errorCode;
    }
}