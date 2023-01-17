<?php

namespace App\Http\Controllers;
use App\Models\types as type;


use Illuminate\Http\Request;

class types extends Controller
{
    function loadAll(){
        $data = type::all();
        return["success"=>true,"data"=>$data];
    }
}
