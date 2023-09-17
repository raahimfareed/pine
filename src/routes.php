<?php
use Pine\App\Route;
use Pine\App\Request;

Route::get("/", fn () => "Hello, World");
Route::get("/post", fn () => "This is a post route");
Route::get("/", fn () => "Line by line routes are overwritten");

Route::get("/request", function (Request $request) {
    var_dump($request);
    return "";
});

Route::post("/test", fn () => "This is a test response");


