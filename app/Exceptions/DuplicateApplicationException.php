<?php

namespace App\Exceptions;

use RuntimeException;

class DuplicateApplicationException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('You have already submitted an application for this job.');
    }
}
