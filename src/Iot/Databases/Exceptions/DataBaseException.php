<?php

namespace Croak\Iot\Databases\Exceptions;

/**
 * Exception sent when an error while connecting or reading/writing in the database occurs
 */
class DataBaseException extends \Exception
{
    /**
    *@var String    error description
    */
    const DB_CONNECTION_FAILED = "Database is not available, check if line 'extension=php_pdo_sqlite.dll' in php.ini is uncommented";
    const ADD_FAILED = "adding data in database has failed";
    const PREPARE_FAILED = "PDO prepare failed";
    const DB_ATTRIBUTE_FAILED = "failed to defined attributes for the pdo object";

    /**
     * construct the object thanks to a code value (one of the const above)
     * @param string $code        one of the constant above
     */
    public function __construct($code)
    {
        parent::__construct($code);
    }
}