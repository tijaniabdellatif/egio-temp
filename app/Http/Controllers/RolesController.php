<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\ApiController;

class RolesController extends ApiController
{

    
    function getRoles()
    {
        $roles = Role::all();
        if($roles){
            return $this->showAny($roles,200,'Success');
        }else{
            return false;
        }
    }

    function getRoleById($id)
    {
        $role = Role::find($id);
        if($role){
            return $this->showAny($role,200,'Success');
        }else{
            return false;
        }
    }

    function getRolesByUserId($id)
    {
        $roles = Role::find($id)->getRoleNames();
        if($roles){
           return $this->showAny($roles,200,'Success');
        }else{ null; }

    }
}
