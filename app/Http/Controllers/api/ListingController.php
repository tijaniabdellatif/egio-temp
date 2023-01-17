<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ad_media;
use App\Models\ads;
use App\Models\cats;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ListingController extends ApiController
{
    public function getSpotlightAds(Request $request){

        try{

            $cost = 10;
            $filter = '';

            if($request->type){
                $filter .= " AND c.type = '$request->type' ";
            }
            if($request->categorie){
                $filter .= " AND c.id = '$request->categorie' ";
            }
            if($request->univer){
                $filter .= " AND c.parent_cat = '$request->univer'";
            }
            if($request->standing){
                $filter .= " AND c.standing = '$request->standing' ";
            }
            if($request->region){
                $filter .= " AND p.region_id = '$request->region' ";
            }
            if($request->city){
                $filter .= " AND a.loccity = '$request->city' ";
            }
            if($request->neighborhood){
                $filter .= " AND a.locdept = '$request->neighborhood' ";
            }
            if($request->min_price){
                $min_price = $request->min_price;
                $min_price_eur = ($min_price*1) / $cost;
                $filter .= " AND ((a.price >= '$min_price' and a.price_curr <> 'EUR') or (a.price >= '$min_price_eur' and a.price_curr = 'EUR')) ";
            }
            if($request->max_price){
                $max_price = $request->max_price;
                $max_price_eur = ($max_price*1) / $cost;
                $filter .= " AND ((a.price <= '$max_price' and a.price_curr <> 'EUR') or (a.price <= '$max_price_eur' and a.price_curr = 'EUR')) ";
            }
            if($request->min_surface){
                $filter .= " AND a.surface >= '$request->min_surface' ";
            }
            if($request->max_surface){
                $filter .= " AND a.surface <= '$request->max_surface' ";
            }
            if($request->rooms){
                $filter .= " AND a.rooms = '$request->rooms' ";
            }
            if($request->bedrooms){
                $filter .= " AND a.bedrooms = '$request->bedrooms' ";
            }
            if($request->bathrooms){
                $filter .= " AND a.bathrooms = '$request->bathrooms' ";
            }
            if($request->age){
                $filter .= " AND a.built_year = $request->age ";
            }
            if($request->jardin===true){
                $filter .= " AND (a.jardin <> '0' and a.jardin is not null) ";
            }
            if($request->piscine===true){
                $filter .= " AND (a.piscine <> '0' and a.piscine is not null) ";
            }
            if($request->parking==true){
                $filter .= " AND (a.parking <> '0' and a.parking is not null) ";
            }
            if($request->meuble==true){
                $filter .= " AND (a.meuble <> '0' and a.meuble is not null) ";
            }
            if($request->security==true){
                $filter .= " AND (a.securite <> '0' and a.securite is not null) ";
            }
            if($request->clime==true){
                $filter .= " AND (a.climatise <> '0' and a.climatise is not null) ";
            }
            if($request->terrasse==true){
                $filter .= " AND (a.terrace <> '0' and a.terrace is not null) ";
            }
            if($request->cave==true){
                $filter .= " AND (a.cave <> '0' and a.cave is not null) ";
            }
            if($request->syndic==true){
                $filter .= " AND (a.syndic <> '0' and a.syndic is not null) ";
            }
            if($request->ascenseur==true){
                $filter .= " AND (a.ascenseur <> '0' and a.ascenseur is not null) ";
            }
            if($request->search && trim($request->search)){
                $search = strtolower(trim($request->search));
                $search2arr = explode(' ',$search);
                $search2 = "";
                foreach($search2arr as $s){
                    if($s){
                        if(strlen($s)>2) $search2.='+'.trim($s);
                        else $search2.='+__'.trim($s);
                    }
                }
                $filter .= " AND
                    (LOWER(a.title) like '%$search%' or LOWER(a.locdept2) like '%$search%'
                    or LOWER(ct.name) like '%$search%' or LOWER(n.name) like '%$search%' or LOWER(u.username) like '%$search%'
                    or LOWER(c.title) like '%$search%' or MATCH (a.keywords,a.description) AGAINST('$search2' IN BOOLEAN MODE)
                ) ";
            }

            $data= DB::select(
                "SELECT a.id , a.title , a.price , a.description , a.price_curr , a.ref , a.surface , a.`rooms` ,
                        a.bedrooms , a.bathrooms , a.loccity , a.locdept , a.locdept2 , ct.name as 'city' , n.name as 'neighborhood' ,
                        a.id_user , u.username , u.usertype , a.catid , c.title as 'categorie' , c.parent_cat , a.published_at as 'date' ,
                        CONCAT(m.path,m.filename,'.',m.extension) AS avatar
                        FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                        LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id LEFT JOIN provinces p on ct.province_id = p.id
                        INNER JOIN options o ON o.ad_id = a.id INNER JOIN options_catalogue op on op.id = o.option_id
                        LEFT JOIN user_info ui on u.id = ui.user_id LEFT JOIN media m on ui.avatar = m.id
                        WHERE o.status = '10' and TIMESTAMPADD(DAY,op.duration ,o.timestamp) > CURRENT_TIMESTAMP
                        and op.type_id = 1 and a.status = '10' and a.expiredate > CURRENT_TIMESTAMP
                        $filter
                        order by RAND() limit 2
            ");

            $result = [];

            foreach ($data as $value) {
                $obj = $value;
                $obj->images = DB::select('CALL adsImages(?)',array($value->id));
                //$obj->videos = DB::select('CALL adsVideos(?)',array($value->id));
                //$obj->audios = DB::select('CALL adsAudios(?)',array($value->id));
                $obj->premium = true;
                $result [] = $obj;
            }

            return $this->showAny($result);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }
    }

    public function getListingAds(Request $request){

        try{
            $cost = 10;
            $validator = Validator::make($request->all(), [
                'from' => 'required|integer',
                'count' => 'required|integer',
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }
            $order = " order by a.published_at DESC ";
            $filter = "";
            if($request->usertype=="pro"){
                $filter .= " AND ( u.usertype = 3 OR u.usertype = 4 ) ";
            }
            if($request->usertype=="par"){
                $filter .= " AND ( u.usertype <> 3 AND u.usertype <> 4 ) ";
            }
            if($request->type){
                $filter .= " AND c.type = '$request->type' ";
            }
            if($request->categorie){
                $filter .= " AND c.id = '$request->categorie' ";
            }
            if($request->univer){
                $filter .= " AND c.parent_cat = '$request->univer' ";
            }
            if($request->standing){
                $filter .= " AND c.standing = '$request->standing' ";
            }
            if($request->region){
                $filter .= " AND p.region_id = '$request->region' ";
            }
            if($request->city){
                $filter .= " AND a.loccity = '$request->city' ";
            }
            if($request->neighborhood){
                $filter .= " AND a.locdept = '$request->neighborhood' ";
            }
            if($request->min_price){
                $min_price = $request->min_price;
                $min_price_eur = ($min_price*1) / $cost;
                $filter .= " AND ((a.price >= '$min_price' and a.price_curr <> 'EUR') or (a.price >= '$min_price_eur' and a.price_curr = 'EUR')) ";
            }
            if($request->max_price){
                $max_price = $request->max_price;
                $max_price_eur = ($max_price*1) / $cost;
                $filter .= " AND ((a.price <= '$max_price' and a.price_curr <> 'EUR') or (a.price <= '$max_price_eur' and a.price_curr = 'EUR')) ";
            }
            if($request->min_surface){
                $filter .= " AND a.surface >= '$request->min_surface' ";
            }
            if($request->max_surface){
                $filter .= " AND a.surface <= '$request->max_surface' ";
            }
            if($request->rooms){
                $filter .= " AND a.rooms = '$request->rooms' ";
            }
            if($request->bedrooms){
                $filter .= " AND a.bedrooms = '$request->bedrooms' ";
            }
            if($request->bathrooms){
                $filter .= " AND a.bathrooms = '$request->bathrooms' ";
            }
            if($request->age){
                $filter .= " AND a.built_year = $request->age ";
            }
            if($request->jardin==true){
                $filter .= " AND (a.jardin <> '0' and a.jardin is not null) ";
            }
            if($request->piscine==true){
                $filter .= " AND (a.piscine <> '0' and a.piscine is not null) ";
            }
            if($request->parking==true){
                $filter .= " AND (a.parking <> '0' and a.parking is not null) ";
            }
            if($request->meuble==true){
                $filter .= " AND (a.meuble <> '0' and a.meuble is not null) ";
            }
            if($request->security==true){
                $filter .= " AND (a.securite <> '0' and a.securite is not null) ";
            }
            if($request->clime==true){
                $filter .= " AND (a.climatise <> '0' and a.climatise is not null) ";
            }
            if($request->terrasse==true){
                $filter .= " AND (a.terrace <> '0' and a.terrace is not null) ";
            }
            if($request->cave==true){
                $filter .= " AND (a.cave <> '0' and a.cave is not null) ";
            }
            if($request->syndic==true){
                $filter .= " AND (a.syndic <> '0' and a.syndic is not null) ";
            }
            if($request->ascenseur==true){
                $filter .= " AND (a.ascenseur <> '0' and a.ascenseur is not null) ";
            }
            if($request->search && trim($request->search)){
                $search = strtolower(trim($request->search));
                $search2arr = explode(' ',$search);
                $search2 = "";
                foreach($search2arr as $s){
                    if($s){
                        if(strlen($s)>2) $search2.='+'.trim($s);
                        else $search2.='+__'.trim($s);
                    }
                }
                $filter .= " AND
                    (LOWER(a.title) like '%$search%' or a.id = '$search' or LOWER(a.locdept2) like '%$search%'
                    or LOWER(ct.name) like '%$search%' or LOWER(n.name) like '%$search%' or LOWER(u.username) like '%$search%'
                    or LOWER(c.title) like '%$search%' or MATCH (a.keywords,a.description) AGAINST('$search2' IN BOOLEAN MODE)
                ) ";
            }
            if ($request->sort == 'price') {
                $order = " ORDER BY a.price ASC ";
            }
            if ($request->sort == 'date') {
                $order = " ORDER BY a.published_at ASC ";
            }
            if ($request->sort == 'priceD') {
                $order = " ORDER BY a.price DESC ";
            }
            if ($request->sort == 'dateD') {
                $order = " ORDER BY a.published_at DESC ";
            }
            $premiums= DB::select(
                "SELECT a.id , a.title , a.price , a.description , a.price_curr , a.ref , a.surface , a.`rooms` ,
                        a.bedrooms , a.bathrooms , a.loccity , a.locdept , a.locdept2 , ct.name as 'city' , n.name as 'neighborhood' ,
                        a.id_user , u.username , u.usertype , a.catid , c.slug , c.title as 'categorie' , c.parent_cat , a.published_at as 'date' ,
                        CONCAT(m.path,m.filename,'.',m.extension) AS avatar , a.loclat , a.loclng
                        FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                        LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id LEFT JOIN provinces p on ct.province_id = p.id
                        INNER JOIN options o ON o.ad_id = a.id INNER JOIN options_catalogue op on op.id = o.option_id
                        LEFT JOIN user_info ui on u.id = ui.user_id LEFT JOIN media m on ui.avatar = m.id
                        WHERE o.status = '10' and TIMESTAMPADD(DAY,op.duration ,o.timestamp) > CURRENT_TIMESTAMP
                        and op.type_id = 2 and a.status = '10' and a.expiredate > CURRENT_TIMESTAMP
                        $filter
                        order by RAND() limit 4
            ");
            $list = DB::select(
                "SELECT a.id , a.title , a.price , a.description , a.price_curr , a.ref , a.surface ,a.`rooms` ,
                        a.bedrooms , a.bathrooms , a.loccity , a.locdept , a.locdept2 , ct.name as 'city' ,n.name as 'neighborhood' ,
                        a.id_user , u.username , u.usertype , a.catid , c.slug , c.title as 'categorie' , c.parent_cat , a.published_at as 'date' ,
                        CONCAT(m.path,m.filename,'.',m.extension) AS avatar , a.loclat , a.loclng
                        FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                        LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id LEFT JOIN provinces p on ct.province_id = p.id
                        LEFT JOIN user_info ui on u.id = ui.user_id LEFT JOIN media m on ui.avatar = m.id
                        WHERE a.status = '10' and a.expiredate > CURRENT_TIMESTAMP
                        $filter
                        $order limit $request->from , $request->count
            ");
            $total = ads::where('status','=','10')->where('expiredate','>',Carbon::now())->count();
            $dataTotal = DB::select(
                "SELECT COUNT(a.id) as 'count'
                        FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                        LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id LEFT JOIN provinces p on ct.province_id = p.id
                        WHERE a.status = '10' and a.expiredate > CURRENT_TIMESTAMP
                        $filter
            ");

            $searchTotal = $dataTotal[0]?$dataTotal[0]->count:0;
            $result = [];

            foreach ($premiums as $value) {
                $obj = $value;
                $obj->images = DB::select('CALL adsImages(?)',array($value->id));
                //$obj->videos = DB::select('CALL adsVideos(?)',array($value->id));
                //$obj->audios = DB::select('CALL adsAudios(?)',array($value->id));
                $obj->premium = true;
                $result [] = $obj;
            }

            foreach ($list as $value) {
                $obj = $value;
                $obj->images = DB::select('CALL adsImages(?)',array($value->id));
                //$obj->videos = DB::select('CALL adsVideos(?)',array($value->id));
                //$obj->audios = DB::select('CALL adsAudios(?)',array($value->id));
                $obj->premium = false;
                $obj->filter = $filter;
                $result [] = $obj;
            }

            return $this->showAny(["data"=>$result,"total"=>$total,"searchTotal"=>$searchTotal]);

        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }
    }

    public function getCategoryById(Request $request){
        return $this->showAny(cats::find($request->id));
    }

    public function mapPopupAdById(Request $request){
        $ad = ads::select('ads.id','ads.title','ads.price','ads.price_curr',DB::raw("CONCAT(media.path,media.filename,'.',media.extension) AS img"))->leftJoin('ad_media','ad_media.ad_id','=','ads.id')
            ->leftJoin('media','media.id','=','ad_media.media_id')->where('ads.id','=',$request->id)->first();
        return $this->showAny($ad);
    }

    public function listingProjects(Request $request){

        try{
            $cost = 10;
            $validator = Validator::make($request->all(), [
                'from' => 'required|integer',
                'count' => 'required|integer',
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }
            $filter = "";
            if($request->type){
                $filter .= " AND c.type = '$request->type' ";
            }
            if($request->categorie){
                $filter .= " AND c.id = '$request->categorie' ";
            }
            if($request->univer){
                $filter .= " AND c.parent_cat = '$request->univer' ";
            }
            if($request->standing){
                $filter .= " AND c.standing = '$request->standing' ";
            }
            if($request->region){
                $filter .= " AND p.region_id = '$request->region' ";
            }
            if($request->city){
                $filter .= " AND a.loccity = '$request->city' ";
            }
            if($request->neighborhood){
                $filter .= " AND a.locdept = '$request->neighborhood' ";
            }
            if($request->min_price){
                $min_price = $request->min_price;
                $min_price_eur = ($min_price*1) / $cost;
                $filter .= " AND ((a.price >= '$min_price' and a.price_curr <> 'EUR') or (a.price >= '$min_price_eur' and a.price_curr = 'EUR')) ";
            }
            if($request->max_price){
                $max_price = $request->max_price;
                $max_price_eur = ($max_price*1) / $cost;
                $filter .= " AND ((a.price <= '$max_price' and a.price_curr <> 'EUR') or (a.price <= '$max_price_eur' and a.price_curr = 'EUR')) ";
            }
            if($request->min_surface){
                $filter .= " AND a.surface >= '$request->min_surface' ";
            }
            if($request->max_surface){
                $filter .= " AND a.surface <= '$request->max_surface' ";
            }
            if($request->rooms){
                $filter .= " AND a.rooms = '$request->rooms' ";
            }
            if($request->bedrooms){
                $filter .= " AND a.bedrooms = '$request->bedrooms' ";
            }
            if($request->bathrooms){
                $filter .= " AND a.bathrooms = '$request->bathrooms' ";
            }
            if($request->age){
                $filter .= " AND a.built_year = $request->age ";
            }
            if($request->jardin==true){
                $filter .= " AND (a.jardin <> '0' and a.jardin is not null) ";
            }
            if($request->piscine==true){
                $filter .= " AND (a.piscine <> '0' and a.piscine is not null) ";
            }
            if($request->parking==true){
                $filter .= " AND (a.parking <> '0' and a.parking is not null) ";
            }
            if($request->meuble==true){
                $filter .= " AND (a.meuble <> '0' and a.meuble is not null) ";
            }
            if($request->security==true){
                $filter .= " AND (a.securite <> '0' and a.securite is not null) ";
            }
            if($request->clime==true){
                $filter .= " AND (a.climatise <> '0' and a.climatise is not null) ";
            }
            if($request->terrasse==true){
                $filter .= " AND (a.terrace <> '0' and a.terrace is not null) ";
            }
            if($request->cave==true){
                $filter .= " AND (a.cave <> '0' and a.cave is not null) ";
            }
            if($request->syndic==true){
                $filter .= " AND (a.syndic <> '0' and a.syndic is not null) ";
            }
            if($request->ascenseur==true){
                $filter .= " AND (a.ascenseur <> '0' and a.ascenseur is not null) ";
            }
            if($request->search && trim($request->search)){
                $search = strtolower(trim($request->search));
                $search2arr = explode(' ',$search);
                $search2 = "";
                foreach($search2arr as $s){
                    if($s){
                        if(strlen($s)>2) $search2.='+'.trim($s);
                        else $search2.='+__'.trim($s);
                    }
                }
                $filter .= " AND
                    (LOWER(a.title) like '%$search%' or LOWER(a.locdept2) like '%$search%'
                    or LOWER(ct.name) like '%$search%' or LOWER(n.name) like '%$search%' or LOWER(u.username) like '%$search%'
                    or LOWER(c.title) like '%$search%' or MATCH (a.keywords,a.description) AGAINST('$search2' IN BOOLEAN MODE)
                ) ";
            }
            $list = DB::select(
                "SELECT a.id , a.title , a.price , a.description , a.price_curr , a.ref , a.surface ,a.`rooms` ,
                        a.bedrooms , a.bathrooms , a.loccity , a.locdept , a.locdept2 , ct.name as 'city' ,n.name as 'neighborhood' ,
                        a.id_user , u.username , u.usertype , a.catid , c.title as 'categorie' , c.parent_cat , a.published_at as 'date' ,
                        CONCAT(m.path,m.filename,'.',m.extension) AS avatar , a.loclat , a.loclng
                        FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                        LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id LEFT JOIN provinces p on ct.province_id = p.id
                        LEFT JOIN user_info ui on u.id = ui.user_id LEFT JOIN media m on ui.avatar = m.id
                        WHERE a.status = '10' and a.expiredate > CURRENT_TIMESTAMP AND a.is_project = 1
                        $filter
                        order by -a.project_priority DESC
                        limit $request->from , $request->count
            ");
            $total = ads::where('status','=','10')->where('expiredate','>',Carbon::now())->count();
            $dataTotal = DB::select(
                "SELECT COUNT(a.id) as 'count'
                        FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                        LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id LEFT JOIN provinces p on ct.province_id = p.id
                        WHERE a.status = '10' and a.expiredate > CURRENT_TIMESTAMP AND a.is_project = 1
                        $filter
            ");

            $searchTotal = $dataTotal[0]?$dataTotal[0]->count:0;
            $result = [];

            foreach ($list as $value) {
                $obj = $value;
                $obj->images = DB::select('CALL adsImages(?)',array($value->id));
                //$obj->videos = DB::select('CALL adsVideos(?)',array($value->id));
                //$obj->audios = DB::select('CALL adsAudios(?)',array($value->id));
                $obj->premium = false;
                $result [] = $obj;
            }

            return $this->showAny(["data"=>$result,"total"=>$total,"searchTotal"=>$searchTotal]);

        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }
    }

    public function mapPointsProjects(Request $request){

        try{
            $cost = 10;
            $filter = "";
            if($request->type){
                $filter .= " AND c.type = '$request->type' ";
            }
            if($request->categorie){
                $filter .= " AND c.id = '$request->categorie' ";
            }
            if($request->univer){
                $filter .= " AND c.parent_cat = '$request->univer' ";
            }
            if($request->standing){
                $filter .= " AND c.standing = '$request->standing' ";
            }
            if($request->region){
                $filter .= " AND p.region_id = '$request->region' ";
            }
            if($request->city){
                $filter .= " AND a.loccity = '$request->city' ";
            }
            if($request->neighborhood){
                $filter .= " AND a.locdept = '$request->neighborhood' ";
            }
            if($request->min_price){
                $min_price = $request->min_price;
                $min_price_eur = ($min_price*1) / $cost;
                $filter .= " AND ((a.price >= '$min_price' and a.price_curr <> 'EUR') or (a.price >= '$min_price_eur' and a.price_curr = 'EUR')) ";
            }
            if($request->max_price){
                $max_price = $request->max_price;
                $max_price_eur = ($max_price*1) / $cost;
                $filter .= " AND ((a.price <= '$max_price' and a.price_curr <> 'EUR') or (a.price <= '$max_price_eur' and a.price_curr = 'EUR')) ";
            }
            if($request->min_surface){
                $filter .= " AND a.surface >= '$request->min_surface' ";
            }
            if($request->max_surface){
                $filter .= " AND a.surface <= '$request->max_surface' ";
            }
            if($request->rooms){
                $filter .= " AND a.rooms = '$request->rooms' ";
            }
            if($request->bedrooms){
                $filter .= " AND a.bedrooms = '$request->bedrooms' ";
            }
            if($request->bathrooms){
                $filter .= " AND a.bathrooms = '$request->bathrooms' ";
            }
            if($request->age){
                $filter .= " AND a.built_year = $request->age ";
            }
            if($request->jardin==true){
                $filter .= " AND (a.jardin <> '0' and a.jardin is not null) ";
            }
            if($request->piscine==true){
                $filter .= " AND (a.piscine <> '0' and a.piscine is not null) ";
            }
            if($request->parking==true){
                $filter .= " AND (a.parking <> '0' and a.parking is not null) ";
            }
            if($request->meuble==true){
                $filter .= " AND (a.meuble <> '0' and a.meuble is not null) ";
            }
            if($request->security==true){
                $filter .= " AND (a.securite <> '0' and a.securite is not null) ";
            }
            if($request->clime==true){
                $filter .= " AND (a.climatise <> '0' and a.climatise is not null) ";
            }
            if($request->terrasse==true){
                $filter .= " AND (a.terrace <> '0' and a.terrace is not null) ";
            }
            if($request->cave==true){
                $filter .= " AND (a.cave <> '0' and a.cave is not null) ";
            }
            if($request->syndic==true){
                $filter .= " AND (a.syndic <> '0' and a.syndic is not null) ";
            }
            if($request->ascenseur==true){
                $filter .= " AND (a.ascenseur <> '0' and a.ascenseur is not null) ";
            }
            if($request->search && trim($request->search)){
                $search = strtolower(trim($request->search));
                $search2arr = explode(' ',$search);
                $search2 = "";
                foreach($search2arr as $s){
                    if($s){
                        if(strlen($s)>2) $search2.='+'.trim($s);
                        else $search2.='+__'.trim($s);
                    }
                }
                $filter .= " AND
                    (LOWER(a.title) like '%$search%' or LOWER(a.locdept2) like '%$search%'
                    or LOWER(ct.name) like '%$search%' or LOWER(n.name) like '%$search%' or LOWER(u.username) like '%$search%'
                    or LOWER(c.title) like '%$search%' or MATCH (a.keywords,a.description) AGAINST('$search2' IN BOOLEAN MODE)
                ) ";
            }
            $result = DB::select(
                "SELECT a.id , a.loclat , a.loclng
                        FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                        LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id LEFT JOIN provinces p on ct.province_id = p.id
                        WHERE a.status = '10' and a.expiredate > CURRENT_TIMESTAMP AND a.is_project = 1
                        $filter
            ");

            return $this->showAny($result);

        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }
    }

    public function mapPointsAds(Request $request){

        try{
            $cost = 10;
            $filter = "";
            if($request->type){
                $filter .= " AND c.type = '$request->type' ";
            }
            if($request->categorie){
                $filter .= " AND c.id = '$request->categorie' ";
            }
            if($request->univer){
                $filter .= " AND c.parent_cat = '$request->univer' ";
            }
            if($request->standing){
                $filter .= " AND c.standing = '$request->standing' ";
            }
            if($request->region){
                $filter .= " AND p.region_id = '$request->region' ";
            }
            if($request->city){
                $filter .= " AND a.loccity = '$request->city' ";
            }
            if($request->neighborhood){
                $filter .= " AND a.locdept = '$request->neighborhood' ";
            }
            if($request->min_price){
                $min_price = $request->min_price;
                $min_price_eur = ($min_price*1) / $cost;
                $filter .= " AND ((a.price >= '$min_price' and a.price_curr <> 'EUR') or (a.price >= '$min_price_eur' and a.price_curr = 'EUR')) ";
            }
            if($request->max_price){
                $max_price = $request->max_price;
                $max_price_eur = ($max_price*1) / $cost;
                $filter .= " AND ((a.price <= '$max_price' and a.price_curr <> 'EUR') or (a.price <= '$max_price_eur' and a.price_curr = 'EUR')) ";
            }
            if($request->min_surface){
                $filter .= " AND a.surface >= '$request->min_surface' ";
            }
            if($request->max_surface){
                $filter .= " AND a.surface <= '$request->max_surface' ";
            }
            if($request->rooms){
                $filter .= " AND a.rooms = '$request->rooms' ";
            }
            if($request->bedrooms){
                $filter .= " AND a.bedrooms = '$request->bedrooms' ";
            }
            if($request->bathrooms){
                $filter .= " AND a.bathrooms = '$request->bathrooms' ";
            }
            if($request->age){
                $filter .= " AND a.built_year = $request->age ";
            }
            if($request->jardin==true){
                $filter .= " AND (a.jardin <> '0' and a.jardin is not null) ";
            }
            if($request->piscine==true){
                $filter .= " AND (a.piscine <> '0' and a.piscine is not null) ";
            }
            if($request->parking==true){
                $filter .= " AND (a.parking <> '0' and a.parking is not null) ";
            }
            if($request->meuble==true){
                $filter .= " AND (a.meuble <> '0' and a.meuble is not null) ";
            }
            if($request->security==true){
                $filter .= " AND (a.securite <> '0' and a.securite is not null) ";
            }
            if($request->clime==true){
                $filter .= " AND (a.climatise <> '0' and a.climatise is not null) ";
            }
            if($request->terrasse==true){
                $filter .= " AND (a.terrace <> '0' and a.terrace is not null) ";
            }
            if($request->cave==true){
                $filter .= " AND (a.cave <> '0' and a.cave is not null) ";
            }
            if($request->syndic==true){
                $filter .= " AND (a.syndic <> '0' and a.syndic is not null) ";
            }
            if($request->ascenseur==true){
                $filter .= " AND (a.ascenseur <> '0' and a.ascenseur is not null) ";
            }
            if($request->search && trim($request->search)){
                $search = strtolower(trim($request->search));
                $search2arr = explode(' ',$search);
                $search2 = "";
                foreach($search2arr as $s){
                    if($s){
                        if(strlen($s)>2) $search2.='+'.trim($s);
                        else $search2.='+__'.trim($s);
                    }
                }
                $filter .= " AND
                    (LOWER(a.title) like '%$search%' or LOWER(a.locdept2) like '%$search%'
                    or LOWER(ct.name) like '%$search%' or LOWER(n.name) like '%$search%' or LOWER(u.username) like '%$search%'
                    or LOWER(c.title) like '%$search%' or MATCH (a.keywords,a.description) AGAINST('$search2' IN BOOLEAN MODE)
                ) ";
            }
            $result = DB::select(
                "SELECT a.id , a.loclat , a.loclng
                        FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                        LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id LEFT JOIN provinces p on ct.province_id = p.id
                        WHERE a.status = '10' and a.expiredate > CURRENT_TIMESTAMP
                        $filter
            ");

            return $this->showAny($result);

        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }
    }
}
