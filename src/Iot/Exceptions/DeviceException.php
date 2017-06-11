<?php

namespace Croak\Iot\Exceptions;

class DeviceException extends \Exception
{
    const MISSING_KEY = "a key is missing in json representing measure";
    const MISSING_VALUE = "a value is missing in json representing measure";
    const DEVICE_SN = "device Serial Number does not match the given SN pattern. See the api-config.json file to change this regex pattern";

    public function __construct($code)
    {
        parent::__construct($code);
    }

}