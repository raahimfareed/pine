<?php
use Pine\App\Route;
use Pine\App\Request;
use Pine\App\View;
use Pine\Controllers\SampleController;

Route::get("/", fn () => new View("index", ["title" => "Welcome to Pine!", "date" => date("Y-m-d H:i:s", time())]));
Route::get("/controller", [SampleController::class, "index"]);

