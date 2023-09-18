<?php
use Pine\App\Route;
use Pine\App\Request;
use Pine\App\View;

Route::get("/", fn () => new View("index", ["title" => "Welcome to Pine!", "date" => date("Y-m-d H:i:s", time())]));

