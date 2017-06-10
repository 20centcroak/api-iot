<?php

namespace Croak\Iot\Exceptions;

class BuildException extends \Exception
{
    const INIT_TABLE_DEVICE_FAILED = "unable to create table DEVICES";
    const INIT_TABLE_USER_FAILED = "unable to create table USERS";
    const INIT_TABLE_MEASURE_FAILED = "unable to create table MEASURES";
    const INIT_DB_NOT_AVAILABLE = "Database not available";
    const CONFIG_FILE_CREATION_FAILED = "unable to create config file";


    public function __construct($code)
    {
        parent::__construct($code);
    }

}