<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    // getPrivileges
    public function getPrivileges(){
        return view('v2.dashboard.settings.privileges');
    }

    // get Plans page
    public function getPlans(){
        return view('v2.dashboard.settings.plans');
    }

    // get Plans page
    public function getCats(){
        return view('v2.dashboard.settings.cats');
    }

    // get Plans page
    public function getTypes(){
        return view('v2.dashboard.settings.OptionTypes');
    }

    // get Plans page
    public function getStandings(){
        return view('v2.dashboard.settings.standings');
    }

    public function getLinks(){
        return view('v2.dashboard.settings.links');
    }

    public function getCycle(){
        return view('v2.dashboard.settings.lifeCycle');
    }

    public function getSeo(){
        return view('v2.dashboard.settings.seo');
    }

    public function getBanners(){
        return view('v2.dashboard.settings.banners');
    }

    public function getOptions(){
        return view('v2.dashboard.settings.options');
    }

    public function getEmails(){
        return view('v2.dashboard.settings.emails');
    }

    public function getPages(){
        return view('v2.dashboard.settings.pages');
    }

    public function getLocalisation(){
        return view('v2.dashboard.settings.localisation');
    }

    public function getLgs(){
        return view('v2.dashboard.settings.logs');
    }

    public function getNotifications(){
        return view('v2.dashboard.settings.notifications');
    }

}
