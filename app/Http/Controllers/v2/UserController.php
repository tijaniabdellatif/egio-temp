<?php

namespace App\Http\Controllers\v2;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    // get manage controller page
    public function getManage(){
        return view('v2.dashboard.users');
    }

    // get addUser page
    public function addUser(){

        return view('v2.dashboard.add-user');

    }

    // get UpdateUser page
    public function updateUser(){
        return view('v2.dashboard.update-user');
    }

    // get Contracts page
    public function getContracts(){
        return view('v2.dashboard.contracts');
    }

    // addContract
    public function addContract(){
        return view('v2.dashboard.add-contract');
    }

}
