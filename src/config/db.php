<?php

use Pine\App\Util;

return [
    'driver' => Util::env('DB_DRIVER'),
    'host' => Util::env('DB_HOST'),
    'database' => Util::env('DB_NAME'),
    'username' => Util::env('DB_USER'),
    'password' => Util::env('DB_PASS'),
    'charset' => Util::env('DB_CHARSET')
];
