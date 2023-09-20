<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/src/config/bootstrap.php";
require_once __DIR__ . "/src/routes.php";

use Pine\App\Route;

Route::load();

