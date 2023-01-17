<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\property_type as ptt;
use Illuminate\Support\Facades\DB;

class property_type extends Controller
{
    function loadPType(Request $req){
        $search = $req->search;
        $from = $req->from;
        $count = $req->count;
        $filter = '';
        if($search){
            if($filter == '')
                $filter .= "WHERE";
            else
                $filter .= "AND";
            $filter .= " ( (lower(p.`id`) like '%$search%') OR (lower(p.`designation`) like  '%$search%') ) ";
        }
        $data = DB::select("SELECT p.`id` , p.`designation` FROM `proprety_types` p $filter ORDER BY p.`id` DESC LIMIT $from , $count");
        $total = DB::select("SELECT COUNT(p.id) as 'total' FROM `proprety_types` p $filter");
        return["success"=>true,"data"=>$data,"total"=>$total[0]?$total[0]->total:0];
    }
    function delete(Request $req){
        $id = $req->id;
        $check = ptt::where('id',$id)->delete();
        if(!$check){
            return ['success' => false , 'msg' => 'Operation faild'];
        }
        else{
            return ['success' => true];
        }
    }
}
