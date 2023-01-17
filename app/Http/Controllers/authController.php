<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{

    public function loginS(Request $request){
        $credentials = $request->only('email');
        if (Auth::attempt($credentials)) {
            return [
                'success' => true ,
                'msg' => "Bienvenue". Auth::user()->firstname.' '.Auth::user()->lastname,
                'id' => Auth::id(),
                'user' => Auth::user(),
                'token' => Auth::user()->api_token
            ];
        }
        else{
            return ['success' => false , 'msg' => ''];
        }
    }


    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return [
                'success' => true ,
                'msg' => "Bienvenue". Auth::user()->firstname.' '.Auth::user()->lastname,
                'id' => Auth::id(),
                'user' => Auth::user(),
                'token' => Auth::user()->api_token
            ];
        }
        else{
            return ['success' => false , 'msg' => ''];
        }
    }

    public function loginAdmin(Request $request){
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('/');
        }
    }

    public function logoutAdmin(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
