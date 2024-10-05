<?php

namespace Pine\Exceptions;

use Pine\App\Exception;

class ViewNotFound extends Exception
{
    public function __construct(string $templatePath = "")
    {
        $message = "View not found.";
        if (!empty($templatePath)) {
            $message .= " Template: {$templatePath}";
        }

        parent::__construct("errors/500", $message, 500);
    }
}
