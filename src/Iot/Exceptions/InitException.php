<?php

namespace Croak\Iot\Exceptions;

class InitException extends \Exception
{
    const INIT_FAILED = "Init failed";
    const CONFIG_FILE_NOT_FOUND = "Config file not found";
    const CONFIG_FILE_ERROR = "Config file reading error";
    const CONFIG_NOT_INITIALISED = "App not initialised";
    const CONFIG_FILE_CREATION_FAILED = "unable to create config file";

    public function __construct($code)
    {
        parent::__construct($code);
    }

}