<?php

namespace iutnc\deefy\exception;

use Exception;

class InvalidPropertyValueException extends Exception
{
    public function __construct($value)
    {
        parent::__construct("Invalid property value: $value");
    }

}