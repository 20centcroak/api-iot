<?php

namespace Croak\Iot\Exceptions;

class MeasureException extends \Exception
{
    const MISSING_KEY = "a key is missing in json representing measure";
    const MISSING_VALUE = "a value is missing in json representing measure";
    const DB_CONNECTION_FAILED = "Database is not available";
    const ADD_FAILED = "adding measure in database has failed";

    public function __construct($code)
    {
        parent::__construct($code);
    }

}