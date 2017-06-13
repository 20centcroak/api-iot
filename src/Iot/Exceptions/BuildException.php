<?php

namespace Croak\Iot\Exceptions;

/**
 * Exception sent when an error while building the app during the first launch
 */
class BuildException extends \Exception
{
    /**
    *@var String    error description
    */
    const INIT_TABLE_DEVICE_FAILED = "unable to create table DEVICES";
    const INIT_TABLE_USER_FAILED = "unable to create table USERS";
    const INIT_TABLE_MEASURE_FAILED = "unable to create table MEASURES";
    const CREATE_DB_DIRECTORY_FAILED = "database directory creation has failed";

    /**
     * construct the object thanks to a code value (one of the const above)
     * @param string $code        one of the constant above
     */
    public function __construct($code)
    {
        parent::__construct($code);
    }
}