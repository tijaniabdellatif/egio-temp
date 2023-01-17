<?php

namespace App\Http\Controllers\v2;

use App\Models\Region;
use App\Models\provinces;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    //get home page
    public function getHome()
    {
        $regions = Region::all('name');
        return view('v2.home',compact('regions'));
    }

    public function getFavoris()
    {
        return view('v2.favorisList');
    }

}
