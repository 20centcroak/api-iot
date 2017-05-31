<?php

namespace Croak\Iot\Exceptions;

class InitException extends \Exception
{
    const INIT_FILE_FAILED = "unable to update config file";
    const INIT_TABLE_DEVICE_FAILED = "unable to create table DEVICES";
    const INIT_TABLE_USER_FAILED = "unable to create table USERS";
    const INIT_TABLE_MEASURE_FAILED = "unable to create table MEASURES";
    const INIT_DB_NOT_AVAILABLE = "Database not available";
    const INIT_FAILED = "Init failed";

    public function __construct($code)
    {
        parent::__construct($code);
    }

}