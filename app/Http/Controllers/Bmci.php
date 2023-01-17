<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Bmci extends Controller
{
    public function index(){
        return view('bmci.index');
    }

    public function showPage(){

          return view('bmci.show');
    }
}
