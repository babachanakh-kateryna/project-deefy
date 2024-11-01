<?php

namespace iutnc\deefy\exception;
use Exception;

// Exception class for authentication errors
class AuthnException extends Exception
{
    public function __construct($message)
    {
        parent::__construct("Auth error: $message");
    }
}