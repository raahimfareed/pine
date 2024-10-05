<?php

use Pine\App\ErrorHandler;

set_exception_handler([ErrorHandler::class, 'handleException']);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../..");
$dotenv->safeLoad();
