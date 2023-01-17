<?php

namespace App\Http\Controllers;
use App\Models\standings as standing;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class standings extends Controller
{
    function loadAll(){
        $data = standing::all();
        return["success"=>true,"data"=>$data];
    }

    function loadStanding(Request $req){
        $search = $req->search;
        $from = $req->from;
        $count = $req->count;
        $filter = '';
        if($search){
            if($filter == '')
                $filter .= "WHERE";
            else
                $filter .= "AND";
            $filter .= " ( (lower(s.`id`) like '%$search%') OR (lower(s.`designation`) like  '%$search%') ) ";
        }
        $data = DB::select("SELECT s.`id` , s.`designation` FROM `standing` s $filter ORDER BY s.`id` DESC LIMIT $from , $count");
        $total = DB::select("SELECT COUNT(s.id) as 'total' FROM `standing` s $filter");
        return["success"=>true,"data"=>$data,"total"=>$total[0]?$total[0]->total:0];
    }
    
    function delete(Request $req){
        $id = $req->id;
        $check = standing::where('id',$id)->delete();
        if(!$check){
            return ['success' => false , 'msg' => 'Operation faild'];
        }
        else{
            return ['success' => true];
        }
    }
}
