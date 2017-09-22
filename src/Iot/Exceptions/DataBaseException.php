<?php

namespace Croak\Iot\Exceptions;

/**
 * Exception sent when an error while connecting or reading/writing in the database occurs
 */
class DataBaseException extends Exception
{
    /**
    *@var String    error description
    */
    const DB_CONNECTION_FAILED = "Database is not available, check if line 'extension=php_pdo_sqlite.dll' in php.ini is uncommented";
    const ADD_FAILED = "Adding data in database has failed";
    const QUERY_FAILED = "Query is not as expected";
    const QUERY_EXECUTION_FAILED = "Query execution has failed";
    const DB_SETTINGS_FAILED = "Failed to defined settings for database";

    /**
     * construct the object thanks to a code value (one of the const above)
     * @param string $code        one of the constant above
     */
    public function __construct($code)
    {
        parent::__construct($code);
    }
}