<?php
use Pine\App\Route;

Route::get("/", fn () => "Hello, World");
Route::get("/post", fn () => "This is a post route");
Route::get("/", fn () => "Line by line routes are overwritten");

Route::post("/test", fn () => "This is a test response");


