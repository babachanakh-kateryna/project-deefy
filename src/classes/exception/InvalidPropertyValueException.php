<?php

namespace iutnc\deefy\exception;

use Exception;

class InvalidPropertyValueException extends Exception
{
    public function __construct($value)
    {
        $safeValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        parent::__construct("Invalid property value: $safeValue");        }

}