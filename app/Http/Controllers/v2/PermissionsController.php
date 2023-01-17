<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\ApiController;
use Spatie\Permission\Models\Permission;

class PermissionsController extends ApiController
{
 
    /*Creation*/
    function create(Request $request)
    {
        $Permission = new Permission;
        $Permission = Permission::create(['name' => $request->name()]);
        if($Permission){
            $this->showAny($Permission,200,'Success');
        }else{ null; }
    }

    function update(Request $request ,$id)
    {

        $update = Permission::find($id)->update(['name'=> $request->name]);
        if($update){
            return $this->showAny($update,200,'Success');
        }else { return null; }

    }

    function destroy($id)
    {

        $destroy = Permission::find($id)->delete();
        if($destroy){
            return $this->showAny($destroy,200,'Success');
        }else{
            return null;
        }

    }

    function getPermissions()
    {
        $Permissions = Permission::all();
        if($Permissions) {
            return $this->showAny($Permissions,200,'Success') ;
        }else{ null; }
    }

    function getPermissionById($id)
    {
        $Permission = Permission::find($id);
        if($Permission){
           return $this->showAny($Permission,200,'Success');
        }else{ null; }

    }

    function getPermissionsByUserId($id)
    {
        $Permissions = Permission::find($id)->getAllPermissions();
        if($Permissions){
           return $this->showAny($Permissions,200,'Success');
        }else{ null; }

    }
    

    public function givePermission($id,Request $request)
    {
        
        $role = Role::find($id);
        $permissionName = $request->name;
        $granted = $role->givePermissionTo($permissionName);

        if($granted){

            return $this->showAny($permissionName,200,'Success');
        }

        return;
    }
    
}

