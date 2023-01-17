<?php

namespace App\Http\Controllers\v2;

use App\Models\settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddController extends Controller
{
    public function getAdd()
    {
        if(Auth()->user()&&Auth()->user()->usertype==3) return redirect('/new-project');
        $settings = settings::first();
        $imageSize = round($settings->image_max_size / 1024);
        $videoSize = round($settings->video_max_size / 1024);

        return view('v2.add', compact('imageSize', 'videoSize'));
    }

    public function getAddProject(){
        if(!Auth()->user()) return redirect('/');
        if(Auth()->user()->usertype!=3) return redirect('/deposer-annonce');
        $settings = settings::first();
        $imageSize = round($settings->image_max_size / 1024);
        $videoSize = round($settings->video_max_size / 1024);

        return view('v2.addProject', compact('imageSize', 'videoSize'));
    }
}
