<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailController extends Controller
{

    // get emailsPage
    public function emailsPage()
    {
        return view('v2.dashboard.emails');
    }


}
