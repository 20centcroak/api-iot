<?php

namespace Croak\Iot\Exceptions;

/**
 * Exception sent when an error occured when defining a device
 */
class DeviceException extends \Exception
{
    /**
    *@var String    error description
    */
    const MISSING_KEY = "a key is missing in json representing SN";
    const MISSING_VALUE = "a value is missing in json representing SN";
    const DEVICE_SN = "device Serial Number does not match the given SN pattern. See the api-config.json file to change this regex pattern";

    /**
     * construct the object thanks to a code value (one of the const above)
     * @param string $code        one of the constant above
     */
    public function __construct($code)
    {
        parent::__construct($code);
    }

}