<?php

namespace Croak\Iot\Databases\Exceptions;

class DataBaseException extends \Exception
{
    protected $message = 'unable to connect to database';
    const DB_CONNECTION_FAILED = "Database is not available";
    const ADD_FAILED = "adding data in database has failed";

    public function __construct($code)
    {
        parent::__construct($code);
    }
}