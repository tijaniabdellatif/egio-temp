<?php

namespace App\Http\Controllers\v2;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    //get dashboard page
    public function getHome()
    {

        return view('v2.dashboard.home');
    }

    // get dashboard test page
    public function getTest()
    {
        return view('v2.dashboard.test');
    }

    // get dashboard test page
    public function getNew_ad()
    {
        return view('v2.dashboard.add-ad');
    }

    public function getProfileUser(){



        return view('v2.dashboard.profile');
    }

}
