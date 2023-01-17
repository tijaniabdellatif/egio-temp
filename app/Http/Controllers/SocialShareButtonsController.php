<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Share;

class SocialShareButtonsController extends Controller
{
    public function ShareWidget(Request $request)
    {


        $urlShare = [

            'facebook' => (object)[
                'uri' => 'https://www.facebook.com/sharer/sharer.php?u='.$request->fullUrl(),
                'icon' => 'fa-brands fa-facebook'
            ],

            'whatsapp' => (object)[

                 "uri" => 'https://wa.me/?text='.$request->fullUrl(),
                 "icon" => 'fa-brands fa-whatsapp'
            ]

        ];





    }
}
