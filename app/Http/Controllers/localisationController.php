<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class localisationController extends Controller
{
    private $test;

    public function __construct(Test $test)
    {
        $this->test = $test;

    }

    public function show(){

    }

}
