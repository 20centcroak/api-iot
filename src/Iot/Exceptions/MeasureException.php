<?php

namespace Croak\Iot\Exceptions;

class MeasureException extends \Exception
{
    const MISSING_KEY = "a key is missing in json representing measure";
    const MISSING_VALUE = "a value is missing in json representing measure";

    public function __construct($code)
    {
        parent::__construct($code);
    }

}