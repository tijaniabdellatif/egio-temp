<?php

namespace App\Http\Controllers;
use App\Models\cats as cat;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class cats extends Controller
{
    function loadAll(){
        $data = cat::all();
        return["success"=>true,"data"=>$data];
    }

    function loadCats(Request $req){
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
            $filter .= " c.`status` =  '$status' ";
        }
        if($search){
            if($filter == '')
                $filter .= "WHERE";
            else
                $filter .= "AND";
            $filter .= " ( (lower(c.`id`) like '%$search%') OR (lower(c.`title`) like  '%$search%') ) ";
        }
        $data = DB::select("SELECT c.`id` , c.`title` , c.`status` FROM `cats` c $filter ORDER BY c.`id` DESC LIMIT $from , $count");
        $total = DB::select("SELECT COUNT(c.id) as 'total' FROM `cats` c $filter");
        return["success"=>true,"data"=>$data,"total"=>$total[0]?$total[0]->total:0];
    }

    function deleteCat(Request $req){
        $id = $req->id;
        $check = cat::where('id',$id)->delete();
        if(!$check){
            return ['success' => false , 'msg' => 'Operation failed'];
        }
        else{
            return ['success' => true];
        }
    }
}
