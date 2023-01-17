<?php

namespace App\Http\Controllers\v2;

use App\Models\settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class frontOfficeController extends Controller
{
    public function getDashboard()
    {
        return view('v2.frontdashboard.dashboard');
    }

    public function getPasswordPage(){

        return view("v2.frontdashboard.editmdp");
    }


    public function getMyProfile()
    {
        return view('v2.frontdashboard.myprofile');
    }

    public function getEditProfile()
    {
        return view('v2.frontdashboard.editprofile');
    }

    public function getMyItems()
    {
        $settings = settings::first();
        $imageSize = round($settings->image_max_size / 1024);
        $videoSize = round($settings->video_max_size / 1024);
        return view('v2.frontdashboard.myads', compact('imageSize', 'videoSize'));
    }

    public function getMyEmails()
    {
        return view('v2.frontdashboard.myemails');
    }

    public function getBookings()
    {
        return view('v2.frontdashboard.bookings');
    }

    public function getMyTransactions()
    {
        return view('v2.frontdashboard.mytransactions');
    }

    public function getAbout()
    {

        return view('pages.about.fr');
    }

    public function getInfo()
    {

        return view('pages.info.fr');
    }

    public function getRoles()
    {

        return view('pages.roles.fr');
    }

    public function getPrivacy()
    {

        return view('pages.privacy.fr');
    }

    public function getCookies()
    {

        return view('pages.cookies.fr');
    }
}
