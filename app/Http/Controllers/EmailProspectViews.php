<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailProspectViews extends Controller
{


    public function index(Request $request){

        $param = $request->query("token");
        return view('v2.conditions',[

              'token' => $param
        ]);

    }


}
