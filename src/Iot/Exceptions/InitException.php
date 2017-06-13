<?php

namespace Croak\Iot\Exceptions;

/**
 * Exception sent when an error while building the app during the first launch
 */
class InitException extends \Exception
{
    /**
    *@var String    error description
    */
    const INIT_FAILED = "Init failed";
    const CONFIG_FILE_NOT_FOUND = "Config file not found";
    const CONFIG_FILE_ERROR = "Config file reading error";
    const CONFIG_NOT_INITIALISED = "App not initialised";
    const CONFIG_FILE_CREATION_FAILED = "unable to create config file";

    /**
     * construct the object thanks to a code value (one of the const above)
     * @param string $code        one of the constant above
     */
    public function __construct($code)
    {
        parent::__construct($code);
    }

}