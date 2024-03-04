<?php

namespace Pine\Controllers;

use Pine\App\Controller;
use Pine\App\View;

class SampleController extends Controller
{
    public function index()
    {
        return new View("controller");
    }
}
