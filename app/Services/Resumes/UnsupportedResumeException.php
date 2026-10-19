<?php

namespace App\Services\Resumes;

use RuntimeException;

class UnsupportedResumeException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('The uploaded file is not a supported PDF or DOCX document.');
    }
}
