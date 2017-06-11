<?php

namespace Croak\Iot\Databases\Exceptions;

class DataBaseException extends \Exception
{
    const DB_CONNECTION_FAILED = "Database is not available, check if line 'extension=php_pdo_sqlite.dll' in php.ini is uncommented";
    const ADD_FAILED = "adding data in database has failed";

    public function __construct($code)
    {
        parent::__construct($code);
    }
}