<?php

namespace Croak\Iot\Exceptions;

class BuildException extends \Exception
{
    const INIT_TABLE_DEVICE_FAILED = "unable to create table DEVICES";
    const INIT_TABLE_USER_FAILED = "unable to create table USERS";
    const INIT_TABLE_MEASURE_FAILED = "unable to create table MEASURES";
    const CONFIG_FILE_CREATION_FAILED = "unable to create config file";
    const CREATE_DB_DIRECTORY_FAILED = "database directory creation has failed";

    public function __construct($code)
    {
        parent::__construct($code);
    }
}