<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class settings extends Controller
{
    function getSetting(){
        $data = DB::select("SELECT * FROM `settings`");
        return["success"=>true,"data"=>$data];
    }
}
