<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{


    //get login page
    public function getLogin(Request $request)
    {
        return view('v2.login');


    }

    //get register page
    public function getRegister()
    {
        return view('v2.register');
    }

    // get reset password
    public function getResetPassword()
    {
        return view('v2.resetPassword');
    }

}
