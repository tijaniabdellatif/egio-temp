<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\banners;
use Illuminate\Http\Request;
use App\Models\cities;
use App\Models\cats;
use App\Models\neighborhoods;
use App\Models\meta_description;

class ListController extends Controller
{
    public function getList(Request $request)
    {
        $types = array("vente", "location", "neuf", "vacance");
        $type = null;
        $cityName = null;
        $neighborhoodName = null;
        $categorieTitle = null;
        $categorie = $request->categorie;
        $neighborhood = $request->neighborhood;
        $city = $request->city;
        if($request->type){
            if(in_array($request->type, $types)){
                $type = $request->type;
            }
        }
        if($request->city){
            $cityobj = cities::where('id','=',$request->city)->first();
            if($cityobj) $cityName = $cityobj->name;
        }
        if($request->neighborhood&&$request->city){
            $neighborhoodobj = neighborhoods::where('id','=',$request->neighborhood)->first();
            if($neighborhoodobj) $neighborhoodName = $neighborhoodobj->name;
        }
        if($request->categorie){
            $catsobj = cats::where('id','=',$request->categorie)->first();
            if($catsobj) $categorieTitle = $catsobj->title;
        }
        $meta_title = 'Immobilier';
        if($categorieTitle) {$meta_title = $categorieTitle;}
        if($neighborhoodName) {$meta_title .= " " . $neighborhoodName;}
        if($cityName) {$meta_title .= " " . $cityName;} else $meta_title .= " Maroc";
        if($type&&!$categorieTitle) {$meta_title .= " - " . $type;}
        $billboard_banner = banners::where('position','=','billboard')->where('active','=',1)->first();
        $panorama_banner = banners::where('position','=','panorama')->where('active','=',1)->first();
        $leaderboard_banner = banners::where('position','=','leader')->where('active','=',1)->first();
        $mobile_banner = banners::where('position','=','mobile')->where('active','=',1)->first();
        $right_banner = banners::where('position','=','right')->where('active','=',1)->first();
        $left_banner = banners::where('position','=','left')->where('active','=',1)->first();

        $desc = meta_description::where(function($query) use ($type, $categorie) {
            if($categorie) $query->whereNull('type');
            else if($type==null) $query->whereNull('type');
            else $query->where('type','=',$type);
        })->where('city','=',$city?1:0)->
            where('neighborhood','=',$neighborhood?1:0)->where('cat','=',$categorie?1:0)->first();

        $meta_desc = '';
        if($desc){
            $desc_val = $desc->description;
            $desc_val = str_replace("{{city}}", $cityName, $desc_val);
            $desc_val = str_replace("{{neighborhood}}", $neighborhoodName, $desc_val);
            $desc_val = str_replace("{{cat}}", $categorieTitle, $desc_val);
            $meta_desc = $desc_val;
        }

        return view('v2.list',
            [
               "billboard_banner" => $billboard_banner,
               "panorama_banner" => $panorama_banner,
               "leaderboard_banner" => $leaderboard_banner,
               "mobile_banner" => $mobile_banner,
               "right_banner" => $right_banner,
               "left_banner" => $left_banner,
               "meta_desc" => $meta_desc,
               'meta_title'=>$meta_title
            ]
        );
    }

    public function getProjectsList()
    {
        return view('v2.projectsList');
    }

    public function getMap()
    {
        return view('v2.mapList');
    }

    public function getCostumList(Request $request)
    {
        $types = array("vente", "location", "neuf", "vacance");
        $type = null;
        $city = null;
        $cityName = null;
        $neighborhood = null;
        $neighborhoodName = null;
        $univer = null;
        $categorie = null;
        $categorieTitle = null;
        if($request->type){
            if(in_array($request->type, $types)){
                if($request->type=='neuf') $univer = 3;
                else $type = $request->type;
            }
            else abort(400);
        }
        if($request->city){
            $cityobj = cities::where('name','like',$request->city)->first();
            if(!$cityobj) abort(404);
            else {$city = $cityobj->id;$cityName = $cityobj->name;}
        }
        if($request->neighborhood&&$request->city){
            $neighborhoodobj = neighborhoods::select('neighborhoods.id','neighborhoods.name')->join('cities','cities.id','=','neighborhoods.city_id')
                ->where('neighborhoods.name','like',$request->neighborhood)->where('cities.name','like',$request->city)->first();
            if(!$neighborhoodobj) abort(404);
            else {$neighborhood = $neighborhoodobj->id;$neighborhoodName = $neighborhoodobj->name;}
        }
        if($request->category){
            $catsobj = cats::where('slug','like',$request->category)->first();
            if(!$catsobj) abort(404);
            else {$categorie = $catsobj->id;$categorieTitle = $catsobj->title;$type = $catsobj->type;}
        }
        $meta_title = 'Immobilier';
        if($categorieTitle) {$meta_title = $categorieTitle;}
        if($neighborhoodName) {$meta_title .= " " . $neighborhoodName;}
        if($cityName) {$meta_title .= " " . $cityName;} else $meta_title .= " Maroc";
        if($type && !$categorieTitle) {$meta_title .= " - " . $type;}

        $desc = meta_description::where(function($query) use ($type, $categorie) {
            if($categorie) $query->whereNull('type');
            else if($type==null) $query->whereNull('type');
            else $query->where('type','=',$type);
        })->where('city','=',$city?1:0)->
            where('neighborhood','=',$neighborhood?1:0)->where('cat','=',$categorie?1:0)->first();

        $meta_desc = '';
        if($desc){
            $desc_val = $desc->description;
            $desc_val = str_replace("{{city}}", $cityName, $desc_val);
            $desc_val = str_replace("{{neighborhood}}", $neighborhoodName, $desc_val);
            $desc_val = str_replace("{{cat}}", $categorieTitle, $desc_val);
            $meta_desc = $desc_val;
        }

        $billboard_banner = banners::where('position','=','billboard')->where('active','=',1)->first();
        $panorama_banner = banners::where('position','=','panorama')->where('active','=',1)->first();
        $leaderboard_banner = banners::where('position','=','leader')->where('active','=',1)->first();
        $mobile_banner = banners::where('position','=','mobile')->where('active','=',1)->first();
        $right_banner = banners::where('position','=','right')->where('active','=',1)->first();
        $left_banner = banners::where('position','=','left')->where('active','=',1)->first();
        return view('v2.list',
            [
                "billboard_banner" => $billboard_banner,
                "panorama_banner" => $panorama_banner,
                "leaderboard_banner" => $leaderboard_banner,
                "mobile_banner" => $mobile_banner,
                "right_banner" => $right_banner,
                "left_banner" => $left_banner,
               "categorie" => $categorie,
               "categorieTitle" => $categorieTitle,
               "univer" => $univer,
               "type" => $type,
               "city" => $city,
               "cityName" => $cityName,
               "neighborhood" => $neighborhood,
               "neighborhoodName" => $neighborhoodName,
               "meta_desc" => $meta_desc,
               'meta_title'=>$meta_title
            ]
        );
    }

    public function getCostumMap(Request $request)
    {
        $types = array("vente", "location", "neuf", "vacance");
        $type = null;
        $city = null;
        $cityName = null;
        $neighborhood = null;
        $neighborhoodName = null;
        $univer = null;
        $categorie = null;
        $categorieTitle = null;
        if($request->type){
            if(in_array($request->type, $types)){
                if($request->type=='neuf') $univer = 3;
                else $type = $request->type;
            }
            else abort(400);
        }
        if($request->city){
            $cityobj = cities::where('name','like',$request->city)->first();
            if(!$cityobj) abort(404);
            else {$city = $cityobj->id;$cityName = $cityobj->name;}
        }
        if($request->neighborhood&&$request->city){
            $neighborhoodobj = neighborhoods::select('neighborhoods.id','neighborhoods.name')->join('cities','cities.id','=','neighborhoods.city_id')
                ->where('neighborhoods.name','like',$request->neighborhood)->where('cities.name','like',$request->city)->first();
            if(!$neighborhoodobj) abort(404);
            else {$neighborhood = $neighborhoodobj->id;$neighborhoodName = $neighborhoodobj->name;}
        }
        if($request->category){
            $catsobj = cats::where('slug','like',$request->category)->first();
            if(!$catsobj) abort(404);
            else {$categorie = $catsobj->id;$categorieTitle = $catsobj->title;$type = $catsobj->type;}
        }
        return view('v2.mapList',
            [
               "categorie" => $categorie,
               "categorieTitle" => $categorieTitle,
               "univer" => $univer,
               "type" => $type,
               "city" => $city,
               "cityName" => $cityName,
               "neighborhood" => $neighborhood,
               "neighborhoodName" => $neighborhoodName
            ]
        );
    }

    public function getCostumProjects(Request $request)
    {
        $city = null;
        $cityName = null;
        $neighborhood = null;
        $neighborhoodName = null;
        $univer = null;
        $categorie = null;
        $categorieTitle = null;
        if($request->city){
            $cityobj = cities::where('name','like',$request->city)->first();
            if(!$cityobj) abort(404);
            else {$city = $cityobj->id;$cityName = $cityobj->name;}
        }
        if($request->neighborhood&&$request->city){
            $neighborhoodobj = neighborhoods::select('neighborhoods.id','neighborhoods.name')->join('cities','cities.id','=','neighborhoods.city_id')
                ->where('neighborhoods.name','like',$request->neighborhood)->where('cities.name','like',$request->city)->first();
            if(!$neighborhoodobj) abort(404);
            else {$neighborhood = $neighborhoodobj->id;$neighborhoodName = $neighborhoodobj->name;}
        }
        if($request->category){
            $catsobj = cats::where('slug','like',$request->category)->where('is_project','=','1')->first();
            if(!$catsobj) abort(404);
            else {$categorie = $catsobj->id;$categorieTitle = $catsobj->title;$type = $catsobj->type;}
        }
        return view('v2.projectsList',
            [
               "categorie" => $categorie,
               "categorieTitle" => $categorieTitle,
               "univer" => $univer,
               "city" => $city,
               "cityName" => $cityName,
               "neighborhood" => $neighborhood,
               "neighborhoodName" => $neighborhoodName
            ]
        );
    }
}
