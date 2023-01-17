<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class pagesRedirections extends Controller
{
    public function bannersPage(){
        return view('v2.dashboard.banners');
    }
}
