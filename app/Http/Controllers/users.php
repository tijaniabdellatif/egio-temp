<?php

namespace App\Http\Controllers;
use App\Models\User as user;
use Illuminate\Support\Facades\DB;
use App\Models\UserContacts;

use Illuminate\Http\Request;

class users extends Controller
{

    public function getAll(Request $request){
        $data = user::get();

        return ['success' => true , 'data' => $data];
    }

    function loadUsers(Request $req){
        $search = $req->search;
        $status = $req->status;
        $from = $req->from;
        $count = $req->count;
        $filter = '';
        if($status){
            if($filter == '')
                $filter .= "WHERE";
            else
                $filter .= "AND";
            $filter .= " u.`status` =  '$status' ";
        }
        if($search){
            if($filter == '')
                $filter .= "WHERE";
            else
                $filter .= "AND";
            $filter .= " ( (lower(u.`id`) like '%$search%') OR (lower(u.`firstname`) like  '%$search%') OR (lower(u.`lastname`) like  '%$search%') OR (lower(u.`username`) like '%$search%') OR (lower(u.`email`) like  '%$search%') ) ";
        }
        $data = DB::select("SELECT u.`id` , u.`firstname` , u.`lastname` , u.`username` , u.`email` , u.`phone` , u.`usertype` , u.`created_at` , u.`authtype` , u.`status` FROM `users` u LEFT JOIN `auth_type` a on u.`authtype` = a.`id` LEFT JOIN `user_type` t on t.`id` = u.`usertype` $filter GROUP BY u.`id` ORDER BY u.`id` DESC LIMIT $from , $count");
        $total = DB::select("SELECT COUNT(u.id) as 'total' FROM `users` u LEFT JOIN `auth_type` a on u.`authtype` = a.`id` LEFT JOIN `user_type` t on t.`id` = u.`usertype` $filter");
        return["success"=>true,"data"=>$data,"total"=>$total[0]?$total[0]->total:0];
    }

    function updateStatus(Request $req){
        $id = $req->id;
        $check = user::where('id',$id)->update([
            "status"=>$req->status
        ]);
        if(!$check){
            return ['success' => false , 'msg' => 'Operation faild'];
        }
        else{
            return ['success' => true];
        }
    }

    function getById(Request $req){
        $id = $req->id;
        $userPhones = [];
        $userWtsps = [];
        $userEmails = [];
        $user = user::where('id','=',$id)->first();
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
        return [
            'success' => true,
            'user' => $user,
            "userphones"=>$userPhones,
            "userWtsps"=>$userWtsps,
            "userEmails"=>$userEmails
        ];
    }

}
