<?php

namespace Croak\Iot\Exceptions;

class DataBaseException extends \Exception
{
    protected $message = 'unable to connect to database';
}