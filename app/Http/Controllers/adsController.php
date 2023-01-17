<?php

namespace App\Http\Controllers;

use App\Models\ads;
use Illuminate\Http\Request;

class AdsController extends Controller
{

    /**
     * index returni ng methods
     *
     * @return void
     * test
     */
    public function index(){
        return $this->render('v2.dashboard.ads');
    }

    public function login(){
        return $this->render('v2.dashboard.login');
    }

}
