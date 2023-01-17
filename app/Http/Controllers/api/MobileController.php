<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ads;
use App\Models\cats;

class MobileController extends Controller
{
    public function getAnnonceStories()
    {
        $annonces = new ads;
        return $annonces->with('medias')->get();
    }

    public function getProjects()
    {
        $annonces = new ads;
        $result = $annonces->where('is_project','!=',0)->get();
        return $result;
    }

    public function getBookList()
    {
        $annonces = new ads;

        $cats = new cats;
        $targetCat = $cats::with('childCat')->get();
        $data  = $annonces::with(['cats' => function($query){

            return $query->where('parent_cat',5);
        }])->get();

        foreach($data as $d){
            if($d->cats == null){
                return response()->json([

                    'data' => 'not found'
                ],404);
            }else{
                return($d);
            }
        }

    }

    public function getPrimeList()
    {

        $annonces = new ads;

        $cats = new cats;
        $targetCat = $cats::with('childCat')->get();
        $data  = $annonces::with(['cats' => function($query){

            return $query->where('parent_cat',3);
        }])->get();

        foreach($data as $d){
            if($d->cats == null){
                return response()->json([

                    'data' => 'not found'
                ],404);
            }else{
                return($d);
            }
        }
    }

    public function getHomeList()
    {
        $annonces = new ads;

        $cats = new cats;
        $targetCat = $cats::with('childCat')->get();
        $data  = $annonces::with(['cats' => function($query){

            return $query->where('parent_cat',1);
        }])->get();

        foreach($data as $d){
            if($d->cats == null){
                return response()->json([

                    'data' => 'not found'
                ],404);
            }else{
                return($d);
            }
        }
    }

    public function getLandList()
    {
        $annonces = new ads;

        $cats = new cats;
        $targetCat = $cats::with('childCat')->get();
        $data  = $annonces::with(['cats' => function($query){

            return $query->where('parent_cat',4);
        }])->get();

        foreach($data as $d){
            if($d->cats == null){
                return response()->json([

                    'data' => 'not found'
                ],404);
            }else{
                return($d);
            }
        }
    }

    public function getOfficeList()
    {
        $annonces = new ads;

        $cats = new cats;
        $targetCat = $cats::with('childCat')->get();
        $data  = $annonces::with(['cats' => function($query){

            return $query->where('parent_cat',2);
        }])->get();

        foreach($data as $d){
            if($d->cats == null){
                return response()->json([

                    'data' => 'not found'
                ],404);
            }else{
                return($d);
            }
        }
    }

    public function createAnnonce(Request $req){
        try{

            $validatorArr = [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:5000',
                'catid' => 'required|integer',
                'price' => 'required|numeric',
                'price_curr' => 'required|string',
                'videoembed' => 'nullable|string|max:500',
                'lat' => 'nullable|numeric',
                'long' => 'nullable|numeric',
                'city' => 'required|integer',
                'dept' => 'required|integer',
                'dept2' => 'nullable|string',
                'userid' => 'required|integer',
                'phone' => 'required|integer',
                'phone2' => 'nullable|integer',
                'wtsp' => 'nullable|integer',
                'email' => 'required|integer',
                'rooms' => 'integer|nullable',
                'bedrooms' => 'integer|nullable',
                'wc' => 'integer|nullable',
                'surface' => 'integer|nullable',
                'surfaceTerrace' => 'integer|nullable',
                'surfaceJardin' => 'integer|nullable',
                'contriction_date' => 'integer|nullable',
                'parking' => 'integer|nullable',
                'price_m' => 'integer|nullable',
                'standing' => 'integer|nullable',
                'ref' => 'string|nullable',
                'vr_link' => 'string|nullable',
                'status' => 'required|string',
                'images' => 'array',
                'videos' => 'array',
                'audios' => 'array',
                'nearbyplaces' => 'array'
            ];


            if($req->dept == '-1') $validatorArr['dept'] = 'nullable|integer';
            if($req->is_project == true) {
                $validatorArr['bg_image'] = 'array';
                $validatorArr['surface2'] = 'nullable|integer';
                $validatorArr['project_priority_id'] = 'nullable|integer';
            }
            if($req->is_child == true) {
                $validatorArr['parent_project'] = 'required|integer';
            }

            // validate request data
            $validator = Validator::make($req->all(), $validatorArr);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            $max_user_ads = 0;
            $max_ad_img = 0;
            $max_ad_video = 0;
            $max_ad_audio = 0;
            $finalTime = null;

            $settings = settings::first();
            if($settings){
                $max_user_ads = $settings->max_user_ads;
                $max_ad_img = $settings->max_ad_img;
                $max_ad_video = $settings->max_ad_video;
                $max_ad_audio = $settings->max_ad_audio;
                $finalTime = Carbon::now()->addDays($settings->ads_expire_duration);

            }
            else{
                return $this->errorResponse('Something wrong! settings not found',404);
            }

            $status = $req->status;

            $contract = $this->getContractDataByUser($req->userid);
            if($contract){
                $max_user_ads = $contract->ads_nbr;
                $contract_date = new Carbon($contract->date);
                $finalTime = $contract_date->addDays($contract->duration);
            }

            $adsCount = ads::where('id_user','=',$req->userid)->where('status','=','10')->count();

            if($adsCount>=$max_user_ads && $status=='10'){
                return $this->errorResponse('Vous avez depassé les limites d\'annonces!',409);
            }

            $keywords = makeAdKeyWords($req);

            $ad = new ads();
            DB::beginTransaction();
            $ad->title = $req->title;
            $ad->description = $req->description;
            $ad->catid = $req->catid;
            $ad->price = $req->price;
            $ad->price_curr = $req->price_curr;
            $ad->videoembed = $req->videoembed;
            $ad->loclng = $req->long;
            $ad->loclat = $req->lat;
            $ad->loccity = $req->city;
            if($req->dept != '-1') $ad->locdept = $req->dept;
            $ad->locdept2 = $req->dept2;
            $ad->id_user = $req->userid;
            $ad->phone = $req->phone;
            $ad->phone2 = $req->phone2;
            $ad->wtsp = $req->wtsp;
            $ad->email = $req->email;
            $ad->project_priority = $req->project_priority_id;
            $ad->rooms = $req->rooms;
            $ad->bedrooms = $req->bedrooms;
            $ad->bathrooms = $req->wc;
            $ad->surface = $req->surface;
            $ad->surface2 = $req->surface2??null;
            $ad->built_year = $req->contriction_date;
            $ad->parking = $req->parking;
            $ad->price_surface = $req->price_m;
            $ad->standing = $req->standing;
            $ad->ref = $req->ref;
            $ad->is_project = $req->is_project?1:0;
            $ad->meuble = $req->meuble?1:0;
            $ad->climatise = $req->climatise?1:0;
            $ad->syndic = $req->syndic?1:0;
            $ad->cave = $req->cave?1:0;
            $ad->ascenseur = $req->ascenseur?1:0;
            $ad->securite = $req->securite?1:0;
            $ad->terrace = $req->terrace?1:0;
            $ad->terrace_surface = $req->surfaceTerrace;
            $ad->jardin = $req->jardin?1:0;
            $ad->jardin_surface = $req->surfaceJardin;
            if(isset($req->bg_image[0])) $ad->bg_image = $req->bg_image[0]['id']??null;
            $ad->parent_project = $req->parent_project??null;
            $ad->vr_link = $req->vr_link;
            $ad->status = $status;
            $ad->expiredate = $finalTime;
            $ad->keywords = $keywords;

            // Getting the user mail and username
           if ($ad->email == '-1' ) {
            $Usermail = User::select('email')->where('id','=',$req->userid)->first();
            $username = User::select('username')->where('id','=',$req->userid)->first();
           }


            $data = ([
                'title' => $req->get('title'),
                ]);

                // Mail::send(new SimpleMail(
                //     [
                //         "to" => $Usermail['email'],
                //         "subject" =>"Votre annonce  est désormais publique !",
                //         "view" => "emails.AdPublished.fr",
                //         "data" => [
                //             "title" => $data["title"],
                //             "username" => $username['username'],
                //             ]
                //         ]));

            $check = $ad->save();

            if($check)
            {

                $data = [
                    'name' => auth()->user()->name,
                    'body' => 'Your announce has been added successfuly',
                    'text' => 'Check out the announce',
                    'url' => url('/admin/items'),
                    'subject_id' => $ad->id,
                    'notification_flag' => 'success',
                    'stopNotifications' => true

                ];
                (new LogActivity())->addToLog($ad->id,$req);
                Notification::send(User::first(), new AnnounceNotifications($data));

            }


            $images = $req->images;
            $videos = $req->videos;
            $audios = $req->audios;
            $nearbyplaces = $req->nearbyPlaces;

            if(!$check){
                DB::rollBack();
                return $this->errorResponse('Something wrong!',409);
            }
            else{
                $i=0;
                if(is_array($images)) foreach($images as $key => $value){
                    if($max_ad_img == $key+2) break;
                    try {
                        $media = new ad_media();
                        $media->ad_id = $ad->id;
                        $media->media_id = $value['id'];
                        $media->order = $i++;
                        $media->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $this->errorResponse('Somthing wrong!',409);
                    }
                }
                $i=0;
                if(is_array($videos)) foreach($videos as $key => $value){
                    if($max_ad_video == $key+2) break;
                    try {
                        $media = new ad_media();
                        $media->ad_id = $ad->id;
                        $media->media_id = $value['id'];
                        $media->order = $i++;
                        $media->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $this->errorResponse('Somthing wrong!',409);
                    }
                }
                $i=0;
                if(is_array($audios)) foreach($audios as $key => $value){
                    if($max_ad_audio == $key+2) break;
                    try {
                        $media = new ad_media();
                        $media->ad_id = $ad->id;
                        $media->media_id = $value['id'];
                        $media->order = $i++;
                        $media->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $this->errorResponse('Something wrong!',409);
                    }
                }
                if(is_array($nearbyplaces)) foreach($nearbyplaces as $key => $nearbyplace){
                    try {
                        $nearby_places = new nearby_places();
                        $nearby_places->id_ad = $ad->id;
                        $nearby_places->title = $nearbyplace['title'];
                        $nearby_places->distance = $nearbyplace['distance'];
                        $nearby_places->id_place_type = $nearbyplace['types'];
                        $nearby_places->lat = $nearbyplace['lat'];
                        $nearby_places->lng = $nearbyplace['long'];
                        $nearby_places->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $this->errorResponse('Something wrong!',409);
                    }
                }
                DB::commit();

                if($check)
                {

                    (new LogActivity())->addToLog($ad->id,$req);

                    Notification::send(User::first(), new AnnounceNotifications([
                        'name' => auth()->user()->name,
                        'body' => 'Your announce has been published.',
                        'text' => 'Check out the announce',
                        'url' => url('/admin/items'),
                        'subject_id' => $ad->id,
                        'notification_flag' => 'success'
                    ]));

                }

                $newData = ads::select('ads.id','ads.title','cats.title as category',DB::raw("COUNT(ad_media.ad_id) as nbr_media"),'neighborhoods.name as district','ads.locdept2 as district2','cities.name as city','users.username as user','ads.created_at','ads.updated_at','ads.expiredate','ads.status')
                    ->join('cats','ads.catid', '=', 'cats.id')
                    ->leftjoin('ad_media','ads.id', '=', 'ad_media.ad_id')
                    ->leftjoin('project_priority','ads.project_priority', '=', 'project_priority.id')
                    ->leftjoin('neighborhoods','ads.locdept', '=', 'neighborhoods.id')
                    ->leftjoin('cities','ads.loccity', '=', 'cities.id')
                    ->join('users','ads.id_user', '=', 'users.id')
                    ->where('ads.id', '=',$ad->id)
                    ->first();
                return $this->showAny($newData);
            }
        }catch(\Throwable $th){
            DB::rollBack();
            return $this->errorResponse($th->getMessage(),500);
        }
    }

}
