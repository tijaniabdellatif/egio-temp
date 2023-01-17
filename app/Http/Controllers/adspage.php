<?php

namespace App\Http\Controllers;
use App\Models\places_type;
use App\Models\standings;
use App\Models\types;
use App\Models\cats;
use App\Models\cities;
use App\Models\neighborhoods;
use App\Models\Project_priority;
use App\Models\User;
use App\Models\UserContacts;

use Illuminate\Http\Request;

class adspage extends Controller
{
    function initPage(Request $request){
        $types = types::all();
        $cats = cats::whereNotNull('parent_cat')->get();
        $project_priority = Project_priority::all();
        $standings = standings::all();
        $places_type = places_type::all();
        $cities = cities::all();
        $id = $request->id;
        $userPhones = [];
        $userWtsps = [];
        $userEmails = [];
        if($id){
            $user = User::select('phone','email')->where('id','=',$id)->first();
            $userContacts = UserContacts::where('user_id','=',$id)->get();

            if($user){
                if($user->phone) $userPhones [] = (object)["id"=>-1,"value"=>$user->phone];
                if($user->email) $userEmails [] = (object)["id"=>-1,"value"=>$user->email];
                foreach ($userContacts as $key => $value) {
                    if($value->type=="phone") $userPhones [] = (object)["id"=>$value->id,"value"=>$value->value];
                    if($value->type=="wtsp") $userWtsps [] = (object)["id"=>$value->id,"value"=>$value->value];
                    if($value->type=="email") $userEmails [] = (object)["id"=>$value->id,"value"=>$value->value];
                }
            }
        }

        return[
            "success"=>true,
            "types"=>$types,
            "cats"=>$cats,
            "project_priority"=>$project_priority,
            "standings"=>$standings,
            "places_type"=>$places_type,
            "cities"=>$cities,
            "userphones"=>$userPhones,
            "userWtsps"=>$userWtsps,
            "userEmails"=>$userEmails
        ];
    }

    function loadDeptsByCity(Request $request){
        $data = neighborhoods::where('city_id','=',$request->city)->get();
        return["success"=>true,"data"=>$data];
    }
}
