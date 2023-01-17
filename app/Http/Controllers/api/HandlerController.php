<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use \App\Models\User;


class handlerController extends ApiController
{


    public function updatePassword(Request $request){



            // DB::table('users')->update([
            //     'password' => Hash::make('multilist@2022@');
            // ])


            // return $this->showAny('success',200);

    }



}
