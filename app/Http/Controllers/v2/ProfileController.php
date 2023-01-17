<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
     // getProfile
     public function getProfile($id)
     {
         // check if id is exist
        if (!$id) {
            abort(404);
        }

        $user = User::find($id);
        if(!$user){
            abort(404);
        }

         return view('v2.profile')->with('id', $id);
     }

}
