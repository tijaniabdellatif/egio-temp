<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    //

    // get messagesPage
    public function messagesPage()
    {
        return view('v2.dashboard.messages');
    }
}
