<?php

namespace Pine\Exceptions;

use Pine\App\Exception;

class RouteNotFound extends Exception
{
    public function __construct(string $route)
    {
        $message = "Route not found.";
        if (!empty($route)) {
            $message .= " Path: {$route}";
        }

        parent::__construct("errors/404", $message, 404);
    }
}
