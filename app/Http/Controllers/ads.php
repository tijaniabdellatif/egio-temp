<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\ad_media;
use App\Models\ads as ad;
use Illuminate\Http\Request;
use App\Models\nearby_places;

use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

class ads extends Controller
{
    function loadAdsView(){
        $data = ad::all();

        return view('annonces-table', ["data"=>$data]);
    }

    function loadAds(Request $req){
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
            $filter .= " a.`status` =  '$status' ";
        }
        if($search){
            if($filter == '')
                $filter .= "WHERE";
            else
                $filter .= "AND";
            $filter .= " ( (lower(a.`title`) like '%$search%') OR (lower(a.`loccity`) like  '%$search%') OR (lower(a.`locdept`) like  '%$search%') OR (lower(a.`locregion`) like '%$search%') OR (lower(a.`id`) like  '%$search%') ) ";
        }
        $data = DB::select("SELECT a.`id` , a.`title` , c.title as 'cat' , count(m.`ad_id`) as 'nb_media' , a.`loccity` , a.`locdept` , a.`created_at` , u.`username` , a.`status` FROM `ads` a LEFT JOIN `cats` c on a.`catid` = c.`id` LEFT JOIN `ad_media` m on m.`ad_id` = a.`id` LEFT JOIN `users` u on u.`id` = a.`id_user` $filter GROUP BY a.`id` ORDER BY a.`id` DESC LIMIT $from , $count");
        $total = DB::select("SELECT COUNT(a.id) as 'total' FROM `ads` a LEFT JOIN `cats` c on a.`catid` = c.`id` LEFT JOIN `ad_media` m on m.`ad_id` = a.`id` LEFT JOIN `users` u on u.`id` = a.`id_user` $filter");
        return["success"=>true,"data"=>$data,"total"=>$total[0]?$total[0]->total:0];
    }

    function loadOneAd(Request $req){
        $id = $req->id;
        $data = ad::where('id',$id)->first();
        return["success"=>true,"data"=>$data];
    }

    function updateStatus(Request $req){
        $id = $req->id;
        $check = ad::where('id',$id)->update([
            "status"=>$req->status
        ]);
        if(!$check){
            return ['success' => false , 'msg' => 'Operation faild'];
        }
        else{
            return ['success' => true];
        }
    }

    function editPage(Request $req)
    {
        $id = $req->id;
        $data = ad::where('id',$id)->first();
        $medias = ad_media::where('ad_id',$id)->join('media', 'media_id', '=', 'id')->get();
        $nearby_places = nearby_places::where('id_ad',$id)->get();
        return view('edit-annonce', ["data"=>$data,"medias"=>$medias,"nearby_places"=>$nearby_places]);
    }

    function AddAd(Request $req){
        $ad = new ad();
        $ad->title = $req->title;
        $ad->description = $req->desc;
        $ad->catid = $req->catid;
        $ad->price = $req->price;
        $ad->price_curr = $req->price_curr;
        $ad->videoembed = $req->videoembed;
        $ad->loclng = $req['loc-long'];
        $ad->loclat = $req['loc-lat'];
        $ad->loczipcode = "";
        $ad->loccity = $req->city;
        if($req->dept != '-1') $ad->locdept = $req->dept;
        //$ad->loccity2 = $req->city2;
        $ad->locdept2 = $req->dept2;
        //$ad->locregion = $req->region;
        //$ad->loccountrycode = $req->country;
        $ad->id_user = $req->userid;
        $ad->rooms = $req->rooms;
        $ad->bedrooms = $req->bedrooms;
        $ad->bathrooms = $req->bathrooms;
        $ad->surface = $req->surface;
        $ad->surface2 = "";
        $ad->built_year = $req->built_year;
        $ad->parking = $req->parking;
        $ad->price_surface = $req->price_surface;
        $ad->property_type = $req->property;
        $ad->standing = $req->title;
        $ad->ref = $req->ref;
        $ad->is_project = $req->is_project?true:false;
        $ad->meuble = $req->meuble?true:false;
        $ad->bg_image = null;
        $ad->parent_project = null;
        $ad->vr_link = $req->vr;
        $ad->status = 10;

        $check = $ad->save();


        $medias = $req->medias;
        $nearbyplaces = $req->nearbyplaces;

        if(!$check){
            return back()->with('error', 'Operation faild!');
        }
        else{
            if(is_array($medias)) for($i = 0 ; $i < count($medias) ; $i++){
                try {
                    $media = new ad_media();
                    $media->ad_id = $ad->id;
                    $media->media_id = $medias[$i];
                    $media->save();
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            if(is_array($nearbyplaces)) for($i = 0 ; $i < count($nearbyplaces) ; $i++){
                try {
                    $nearbyplace = json_decode($nearbyplaces[$i]);

                    $nearby_places = new nearby_places();
                    $nearby_places->id_ad = $ad->id;
                    $nearby_places->title = $nearbyplace->title;
                    $nearby_places->distance = $nearbyplace->distance;
                    $nearby_places->id_place_type = $nearbyplace->types;
                    $nearby_places->lat = $nearbyplace->lat;
                    $nearby_places->lng = $nearbyplace->long;
                    $nearby_places->save();
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            return redirect()->intended('/annonces');
        }
    }

    function Edit_Ad(Request $req){
        /*$ad = new ad();
        $ad->id = $req->id;
        $ad->title = $req->title;
        $ad->description = $req->desc;
        $ad->catid = $req->catid;
        $ad->price = $req->price;
        $ad->price_curr = $req->price_curr;
        $ad->videoembed = $req->videoembed;
        $ad->loclng = $req['loc-long'];
        $ad->loclat = $req['loc-lat'];
        $ad->loczipcode = "";
        $ad->loccity = $req->city;
        if($req->dept != '-1') $ad->locdept = $req->dept;
        //$ad->loccity2 = $req->city2;
        $ad->locdept2 = $req->dept2;
        $ad->locregion = $req->region;
        $ad->loccountrycode = $req->country;
        $ad->id_user = $req->userid;
        $ad->rooms = $req->rooms;
        $ad->bedrooms = $req->bedrooms;
        $ad->bathrooms = $req->bathrooms;
        $ad->surface = $req->surface;
        $ad->surface2 = "";
        $ad->built_year = $req->built_year;
        $ad->parking = $req->parking;
        $ad->price_surface = $req->price_surface;
        $ad->property_type = $req->property;
        $ad->standing = $req->title;
        $ad->ref = $req->ref;
        $ad->is_project = $req->is_project?true:false;
        $ad->meuble = $req->meuble?true:false;
        $ad->bg_image = null;
        $ad->parent_project = null;
        $ad->vr_link = $req->vr;
        $ad->status = 40;

        $check = $ad->update();

        $medias = $req->medias;
        $nearbyplaces = $req->nearbyplaces;*/

        $check = true;



        if(!$check){
            return back()->with('error', 'Operation faild!');
        }
        else{
            /*if(is_array($medias)) for($i = 0 ; $i < count($medias) ; $i++){
                try {
                    $media = new ad_media();
                    $media->ad_id = $ad->id;
                    $media->media_id = $medias[$i];
                    $media->save();
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            if(is_array($nearbyplaces)) for($i = 0 ; $i < count($nearbyplaces) ; $i++){
                try {
                    $nearbyplace = json_decode($nearbyplaces[$i]);

                    $nearby_places = new nearby_places();
                    $nearby_places->id_ad = $ad->id;
                    $nearby_places->title = $nearbyplace->title;
                    $nearby_places->distance = $nearbyplace->distance;
                    $nearby_places->id_place_type = $nearbyplace->types;
                    $nearby_places->lat = $nearbyplace->lat;
                    $nearby_places->lng = $nearbyplace->long;
                    $nearby_places->save();
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }*/
            return redirect()->intended('/annonces');
        }
    }
}
