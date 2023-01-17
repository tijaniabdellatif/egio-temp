<?php

namespace App\Http\Controllers\api;

use \App\Models\ads;
use \App\Models\User;
use App\Models\media;
use \App\Models\email;
use App\Models\cities;
use \App\Models\clicks;
use \App\Models\options;
use App\Mail\SimpleMail;
use App\Models\settings;
use \App\Models\ad_media;
use \App\Models\Contract;
use App\Mail\AdPublished;
use App\Events\AdsExpired;
use App\Helpers\ExpiredAds;
use Illuminate\Support\Str;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use \App\Models\UserContacts;
use App\Models\nearby_places;
use App\Models\neighborhoods;
use Illuminate\Support\Carbon;
use App\Http\Controllers\users;
use App\Listeners\AdsExpiredAt;
use \App\Models\options_catalogue;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use GrahamCampbell\ResultType\Success;
use App\Http\Controllers\ApiController;
use App\Models\LogActivity as Activities;
use Illuminate\Support\Facades\Validator;
use App\Notifications\AnnounceNotifications;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TransactionsNotification;

class adsController extends ApiController
{

    const CALL_IMAGES = 'CALL adsImages(?)';


    public function getData()
    {

        $ads = ads::all()->take(5);
        return $this->showAny($ads);
    }

    // filter query
    public function filter(Request $request)
    {

        // build query using $data
        /*$query = cache()->remember('adsFilter', Carbon::now()->addMinute(5),function () use ($request) {
            return ads::select('ads.is_project','ads.id','ads.title','cats.title as category',DB::raw("COUNT(ad_media.ad_id) as nbr_media"),'neighborhoods.name as district','ads.locdept2 as district2','cities.name as city','users.username as user','ads.created_at','ads.updated_at','ads.expiredate','ads.status')
                ->join('cats','ads.catid', '=', 'cats.id')
                ->leftjoin('ad_media','ads.id', '=', 'ad_media.ad_id')
                ->leftjoin('neighborhoods','ads.locdept', '=', 'neighborhoods.id')
                ->leftjoin('cities','ads.loccity', '=', 'cities.id')
                ->join('users','ads.id_user', '=', 'users.id')
                ->orderBy('ads.id', 'desc')
                ->groupBy('ads.id');
        }); */

        $query = ads::select('ads.*', 'cats.title as category', 'cats.parent_cat as univers', DB::raw("COUNT(ad_media.ad_id) as nbr_media"), 'neighborhoods.name as district', 'ads.locdept2 as district2', 'cities.name as city', 'users.username as user', 'user_type.designation as usertype')
            ->join('cats', 'ads.catid', '=', 'cats.id')
            ->leftjoin('ad_media', 'ads.id', '=', 'ad_media.ad_id')
            ->leftjoin('project_priority', 'ads.project_priority', '=', 'project_priority.id')
            ->leftjoin('neighborhoods', 'ads.locdept', '=', 'neighborhoods.id')
            ->leftjoin('cities', 'ads.loccity', '=', 'cities.id')
            ->join('users', 'ads.id_user', '=', 'users.id')
            ->join('user_type', 'users.usertype', '=', 'user_type.id')
            ->orderBy('ads.id', 'desc')
            ->groupBy('ads.id');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'ads.id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.title' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.description' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.catid' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.price' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.price_curr' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.videoembed' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.loclng' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.loclat' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.loccity' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.locdept' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.lcocity2' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.locdept2' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.locregion' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.loccountrycode' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.id_user' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.phone' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.phone2' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.wtsp' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.email' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.likes' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.audio' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.rooms' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.bedrooms' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.bathrooms' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.surface' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.surface2' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.built_year' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.parking' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.price_surface' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.property_type' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.standing' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.ref' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.is_project' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.project_priority' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.bg_image' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.parent_project' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.meuble' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.terrace' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.terrace_surface' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.jardin' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.jardin_surface' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.climatise' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.syndic' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.cave' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.ascenseur' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.securite' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.groundfloor' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.gardenlevel' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.vr_link' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.created_at' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE', '>', '<'],
                ],
                'ads.published_at' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE', '>', '<'],
                ],
                'ads.updated_at' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.expiredate' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.status' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'user_type.id' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'users.username' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'cats.parent_cat' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'cats.title' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],

            ], // allowed cols to filter by
            true // passing data as json (if true) or php array (if false)
        );

        //ddQuery($query);

        try {
            //$query = $query->groupBy('ads.id');
            $result = [];
            //check if query has sort and order
            if (isset($request->sort) && isset($request->order)) {
                $result = $query->orderBy($request->sort, $request->order);
            }

            //check if request has per_page parameter
            if ($request->has('per_page')) {
                // get the data
                $result = $query->paginate($request->per_page);
            } else {
                // get the data
                $result = $query->get();
            }

            //dd($query->toSql());
            // return success message with data
            return $this->showAny($result);
        } catch (\Exception $e) {
            return $this->errorResponse('Check your columns or tables names ' . $e, 500);
        }
    }

    public function statesByAd(Request $request)
    {
        $id = $request->id;
        $dateFrom = $request->dateFrom;
        $dateTo = $request->dateTo;

        $views = clicks::where("ad_id", "=", $id)->where("type", "=", "hit")->whereBetween('date', [$dateFrom, $dateTo])->count();
        //$views = DB::select('CALL getViews(?,?,?)',array($id,$dateFrom,$dateTo));
        $phones = clicks::where("ad_id", "=", $id)->where("type", "=", "phone")->whereBetween('date', [$dateFrom, $dateTo])->count();
        //$phones = DB::select('CALL getPhones(?,?,?)',array($id,$dateFrom,$dateTo));
        $emails = email::where("ad_id", "=", $id)->whereBetween('date', [$dateFrom, $dateTo])->count();
        //$emails = DB::select('CALL getEmails(?,?,?)',array($id,$dateFrom,$dateTo));
        $wtsps = clicks::where("ad_id", "=", $id)->where("type", "=", "wtsp")->whereBetween('date', [$dateFrom, $dateTo])->count();
        //$wtsps = DB::select('CALL getWtsps(?,?,?)',array($id,$dateFrom,$dateTo));

        $result = array('wtsps' => $wtsps, 'emails' => $emails, 'phones' => $phones, 'views' => $views, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo);

        return $this->showAny($result);
    }

    public function statesByUser(Request $request)
    {
        $id = $request->id;
        $dateFrom = $request->dateFrom;
        $dateTo = $request->dateTo;

        $views = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where("ads.id_user", "=", $id)->where("clicks.type", "=", "hit")->whereBetween('clicks.date', [$dateFrom, $dateTo])->count();
        $phones = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where("ads.id_user", "=", $id)->where("clicks.type", "=", "phone")->whereBetween('clicks.date', [$dateFrom, $dateTo])->count();
        $emails = email::join('ads', 'ads.id', '=', 'emails.ad_id')->where("ads.id_user", "=", $id)->whereBetween('emails.date', [$dateFrom, $dateTo])->count();
        $wtsps = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where("ads.id_user", "=", $id)->where("clicks.type", "=", "wtsp")->whereBetween('clicks.date', [$dateFrom, $dateTo])->count();

        $result = array('wtsps' => $wtsps, 'emails' => $emails, 'phones' => $phones, 'views' => $views, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo);

        return $this->showAny($result);
    }

    public function getAdById(Request $request)
    {
        try {
            $id = $request->id;
            $ad = ads::find($id);
            if (!$ad) {
                return $this->errorResponse('Ad not found', 404);
            }
            $images = DB::select('CALL adsImages(?)', array($id));
            $videos = DB::select('CALL adsVideos(?)', array($id));
            $audios = DB::select('CALL adsAudios(?)', array($id));
            $bg_image = DB::select('CALL bgImage(?)', array($id));
            $nearby_places = DB::select('CALL nearbyPlaces(?)', array($id));

            $userPhones = [];
            $userWtsps = [];
            $userEmails = [];
            $user_id = $ad->id_user;
            $user = User::select('phone', 'email')->where('id', '=', $user_id)->first();

            $userContacts = UserContacts::where('user_id', '=', $id)->get();
            if ($user) {
                if ($user->phone) {
                    $userPhones[] = (object)["id" => -1, "value" => $user->phone];
                }
                if ($user->email) {
                    $userEmails[] = (object)["id" => -1, "value" => $user->email];
                }
                foreach ($userContacts as $key => $value) {
                    if ($value->type == "phone") {
                        $userPhones[] = (object)["id" => $value->id, "value" => $value->value];
                    }
                    if ($value->type == "whatsapp") {
                        $userWtsps[] = (object)["id" => $value->id, "value" => $value->value];
                    }
                    if ($value->type == "email") {
                        $userEmails[] = (object)["id" => $value->id, "value" => $value->value];
                    }
                }
            }
            $result = array(
                'ad' => $ad,
                'images' => $images,
                'videos' => $videos,
                'audios' => $audios,
                'bg_image' => $bg_image,
                'nearby_places' => $nearby_places,
                'userPhones' => $userPhones,
                'userWtsps' => $userWtsps,
                'userEmails' => $userEmails
            );
            return $this->showAny($result);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }


    public function addAd(Request $req)
    {


        try {

            $validatorArr = [
                'title' => 'required|max:255',
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


            if ($req->dept == '-1') $validatorArr['dept'] = 'nullable|integer';
            if ($req->is_project == true) {
                $validatorArr['bg_image'] = 'array';
                $validatorArr['surface2'] = 'nullable|integer';
                $validatorArr['project_priority_id'] = 'nullable|integer';
            }
            if ($req->is_child == true) {
                $validatorArr['parent_project'] = 'required|integer';
            }

            // validate request data
            $validator = Validator::make($req->all(), $validatorArr);


            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }

            $max_user_ads = 0;
            $max_ad_img = 0;
            $max_ad_video = 0;
            $max_ad_audio = 0;
            $finalTime = null;

            $settings = settings::first();

            if ($settings) {
                $max_user_ads = $settings->max_user_ads;
                $max_ad_img = $settings->max_ad_img;
                $max_ad_video = $settings->max_ad_video;
                $max_ad_audio = $settings->max_ad_audio;
                $finalTime = Carbon::now()->addDays($settings->ads_expire_duration);
            } else {
                return $this->errorResponse('Something wrong! settings not found', 404);
            }

            $status = $req->status;


            $contract = $this->getContractDataByUser($req->userid);

            if ($contract) {
                $max_user_ads = $contract->ads_nbr;
                $contract_date = new Carbon($contract->date);
                $finalTime = $contract_date->addDays($contract->duration);
            }

            $adsCount = ads::where('id_user', '=', $req->userid)->where('status', '=', '10')->count();



            if ($adsCount >= $max_user_ads && $status == '10') {
                return $this->errorResponse('Vous avez depassé les limites d\'annonces!', 409);
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
            if ($req->dept != '-1') $ad->locdept = $req->dept;
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
            $ad->surface2 = $req->surface2 ?? null;
            $ad->built_year = $req->contriction_date;
            $ad->parking = $req->parking;
            $ad->price_surface = $req->price_m;
            $ad->standing = $req->standing;
            $ad->ref = $req->ref;
            $ad->is_project = $req->is_project ? 1 : 0;
            $ad->meuble = $req->meuble ? 1 : 0;
            $ad->climatise = $req->climatise ? 1 : 0;
            $ad->syndic = $req->syndic ? 1 : 0;
            $ad->cave = $req->cave ? 1 : 0;
            $ad->ascenseur = $req->ascenseur ? 1 : 0;
            $ad->securite = $req->securite ? 1 : 0;
            $ad->terrace = $req->terrace ? 1 : 0;
            $ad->terrace_surface = $req->surfaceTerrace;
            $ad->jardin = $req->jardin ? 1 : 0;
            $ad->jardin_surface = $req->surfaceJardin;
            if (isset($req->bg_image[0])) $ad->bg_image = $req->bg_image[0]['id'] ?? null;
            $ad->parent_project = $req->parent_project ?? null;
            $ad->vr_link = $req->vr_link;
            $ad->status = $status;
            $ad->groundfloor = $req->groundfloor ? 1 : 0;
            $ad->gardenlevel = $req->gardenlevel ? 1 : 0;
            $ad->balcony = $req->balcony ? true : false;
            $ad->green_spaces = $req->green_spaces ? true : false;
            $ad->guardian = $req->guardian ? true : false;
            $ad->automation = $req->automation ? true : false;
            $ad->sea_view = $req->sea_view ? true : false;
            $ad->box = $req->box ? true : false;
            $ad->equipped_kitchen = $req->equipped_kitchen ? true : false;
            $ad->soundproof = $req->soundproof ? true : false;
            $ad->thermalinsulation = $req->thermalinsulation ? true : false;
            $ad->playground = $req->playground ? true : false;
            $ad->gym = $req->gym ? true : false;
            $ad->Chimney = $req->Chimney ? true : false;
            $ad->sportterrains = $req->sportterrains ? true : false;
            $ad->library = $req->library ? true : false;
            $ad->double_orientation = $req->double_orientation ? true : false;
            $ad->intercom = $req->intercom ? true : false;
            $ad->garage = $req->garage ? true : false;
            $ad->double_glazing = $req->double_glazing ? true : false;
            $ad->expiredate = $finalTime;
            $ad->keywords = $keywords;


            // Getting the user mail and username
            if ($ad->email == '-1') {
                $Usermail = User::select('email')->where('id', '=', $req->userid)->first();
                $username = User::select('username')->where('id', '=', $req->userid)->first();


            }






            $adCity = cities::select('name')->where('id', '=', $ad->loccity)->first();


            // get the ad image
            $image = media::select(DB::raw("CONCAT(media.path,media.filename,'.',media.extension) as img"))
                ->join('ad_media', 'ad_media.media_id', '=', 'media.id')
                ->leftjoin('ads', 'ad_media.ad_id', '=', 'ads.id')
                ->where('ads.id', '=', $ad->id)
                ->first();



            // Host name
            $host_name = $_SERVER['HTTP_HOST'];

            // in case there is no image
            if (!$image) {
                $img = "/assets/img/no-photo.png";
            } else {
                $img = $image->img;
            }


            /**
             * Data for emails
             */
            $dataMail = ([
                'title' => $req->get('title'),
                'id' => $req->userid,
            ]);

            // Mail::send(new SimpleMail(
            //     [
            //         "to" => $Usermail['email'],
            //         "subject" => "Votre annonce est désormais publique !",
            //         "view" => "emails.AdPublished.fr",
            //         "data" => [
            //             "id" => $dataMail["id"],
            //             "title" => $dataMail["title"],
            //             "username" => $username['username'],
            //             "city" => $adCity['name'],
            //             "price" => $req->price,
            //             "image" => $img,
            //             "host_name" => $host_name
            //         ]
            //     ]
            // ));

            $check = $ad->save();

            if ($check) {

                $data = [
                    'name' => auth()->user()->username,
                    'body' => $username->username . " a ajouté l'annonce id : " . $ad->id,
                    'text' => "Voir l'announce",
                    'url' => url('./item/' . $ad->id),
                    'subject_id' => $ad->id,
                    'notification_flag' => 'success',

                ];
                (new LogActivity())->addToLog($ad->id, $req);
                Notification::send(User::first(), new AnnounceNotifications($data));
            }


            $images = $req->images;
            $videos = $req->videos;
            $audios = $req->audios;
            $nearbyplaces = $req->nearbyPlaces;

            if (!$check) {
                DB::rollBack();
                return $this->errorResponse('Something wrong!', 409);
            } else {
                $i = 0;
                if (is_array($images)) foreach ($images as $key => $value) {
                    if ($max_ad_img == $key + 2) break;
                    try {
                        $media = new ad_media();
                        $media->ad_id = $ad->id;
                        $media->media_id = $value['id'];
                        $media->order = $i++;
                        $media->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $this->errorResponse('Something wrong!', 409);
                    }
                }
                $i = 0;
                if (is_array($videos)) foreach ($videos as $key => $value) {
                    if ($max_ad_video == $key + 2) break;
                    try {
                        $media = new ad_media();
                        $media->ad_id = $ad->id;
                        $media->media_id = $value['id'];
                        $media->order = $i++;
                        $media->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $this->errorResponse('Somthing wrong!', 409);
                    }
                }
                $i = 0;
                if (is_array($audios)) foreach ($audios as $key => $value) {
                    if ($max_ad_audio == $key + 2) break;
                    try {
                        $media = new ad_media();
                        $media->ad_id = $ad->id;
                        $media->media_id = $value['id'];
                        $media->order = $i++;
                        $media->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $this->errorResponse('Something wrong!', 409);
                    }
                }
                if (is_array($nearbyplaces)) foreach ($nearbyplaces as $key => $nearbyplace) {
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
                        return $this->errorResponse('Something wrong!', 409);
                    }
                }
                DB::commit();

                if ($check) {

                    (new LogActivity())->addToLog($ad->id, $req);

                    Notification::send(User::first(), new AnnounceNotifications([
                        'name' => auth()->user()->name,
                        'body' => auth()->user()->username . " a publié l'annonce: " . $ad->id,
                        'text' => "Visualiser l'annonce",
                        'url' => url('../item/' . $ad->id),
                        'subject_id' => $ad->id,
                        'notification_flag' => 'success'
                    ]));
                }

                $newData = ads::select('ads.id', 'ads.title', 'cats.title as category', DB::raw("COUNT(ad_media.ad_id) as nbr_media"), 'neighborhoods.name as district', 'ads.locdept2 as district2', 'cities.name as city', 'users.username as user', 'ads.created_at', 'ads.updated_at', 'ads.expiredate', 'ads.status')
                    ->join('cats', 'ads.catid', '=', 'cats.id')
                    ->leftjoin('ad_media', 'ads.id', '=', 'ad_media.ad_id')
                    ->leftjoin('project_priority', 'ads.project_priority', '=', 'project_priority.id')
                    ->leftjoin('neighborhoods', 'ads.locdept', '=', 'neighborhoods.id')
                    ->leftjoin('cities', 'ads.loccity', '=', 'cities.id')
                    ->join('users', 'ads.id_user', '=', 'users.id')
                    ->where('ads.id', '=', $ad->id)
                    ->first();
                return $this->showAny($newData);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function updateAd(Request $req)
    {
        try {
            $validatorArr = [
                'id' => 'required',
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

            if ($req->dept == '-1') $validatorArr['dept'] = 'nullable|integer';
            if ($req->is_project == true) {
                $validatorArr['bg_image'] = 'array';
                $validatorArr['surface2'] = 'nullable|integer';
                $validatorArr['project_priority'] = 'nullable|integer';
            }

            // validate request data
            $validator = Validator::make($req->all(), $validatorArr);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }

            $max_user_ads = 0;
            $max_ad_img = 0;
            $max_ad_video = 0;
            $max_ad_audio = 0;
            $finalTime = null;

            $settings = settings::first();
            if ($settings) {
                $max_user_ads = $settings->max_user_ads;
                $max_ad_img = $settings->max_ad_img;
                $max_ad_video = $settings->max_ad_video;
                $max_ad_audio = $settings->max_ad_audio;
                $finalTime = Carbon::now()->addDays($settings->ads_expire_duration);
            } else {
                return $this->errorResponse('Something wrong! settings not found', 404);
            }

            $status = $req->status;

            $contract = $this->getContractDataByUser($req->userid);
            if ($contract) {
                $max_user_ads = $contract->ads_nbr;
                $contract_date = new Carbon($contract->date);
                $finalTime = $contract_date->addDays($contract->duration);
            }

            $adsCount = ads::where('id_user', '=', $req->userid)->where('status', '=', '10')->count();

            if ($adsCount >= $max_user_ads) {
                return $this->errorResponse('You have exceeded ad limits!', 409);
            }

            $keywords = makeAdKeyWords($req);

            // find ad by id
            $ad = ads::find($req->id);


            // if not exist
            if (!$ad) {
                return $this->errorResponse('Ad not found', 404);
            }
            DB::beginTransaction();

            $ad->title = $req->title;
            $ad->description = $req->description ?? $ad->description;
            $ad->catid = $req->catid ?? $ad->catid;
            $ad->price = $req->price ?? $ad->price;
            $ad->price_curr = $req->price_curr ?? $ad->price_curr;
            $ad->videoembed = $req->videoembed ?? $ad->videoembed;
            $ad->loclng = $req->long ?? $ad->loclng;
            $ad->loclat = $req->lat ?? $ad->loclat;
            $ad->loccity = $req->city ?? $ad->loccity;
            if ($req->dept != '-1') $ad->locdept = $req->dept ?? $ad->locdept;
            //$ad->loccity2 = $req->city2;
            $ad->locdept2 = $req->dept2 ?? $ad->locdept2;
            //$ad->locregion = $req->region;
            //$ad->loccountrycode = $req->country;
            $ad->id_user = $req->userid ?? $ad->id_user;
            $ad->phone = $req->phone ?? $ad->phone;
            $ad->phone2 = $req->phone2 ?? $ad->phone2;
            $ad->wtsp = $req->wtsp ?? $ad->wtsp;
            $ad->email = $req->email ?? $ad->email;
            $ad->rooms = $req->rooms ?? $ad->rooms;
            $ad->bedrooms = $req->bedrooms ?? $ad->bedrooms;
            $ad->bathrooms = $req->wc ?? $ad->bathrooms;
            $ad->surface = $req->surface ?? $ad->surface;
            $ad->surface2 = $req->surface2 ?? $ad->surface2;
            $ad->built_year = $req->contriction_date ?? $ad->built_year;
            $ad->parking = $req->parking ?? $ad->parking;
            $ad->price_surface = $req->price_m ?? $ad->price_surface;
            $ad->standing = $req->standing ?? $ad->standing;
            $ad->ref = $req->ref ?? $ad->ref;
            $ad->is_project = $req->is_project ? 1 : 0;
            $ad->project_priority = $req->project_priority ?? $ad->project_priority;
            $ad->meuble = $req->meuble ? 1 : 0;
            $ad->climatise = $req->climatise ? 1 : 0;
            $ad->syndic = $req->syndic ? 1 : 0;
            $ad->cave = $req->cave ? 1 : 0;
            $ad->ascenseur = $req->ascenseur ? 1 : 0;
            $ad->securite = $req->securite ? 1 : 0;
            $ad->terrace = $req->terrace ? 1 : 0;
            $ad->terrace_surface = $req->surfaceTerrace ?? $ad->terrace_surface;
            $ad->jardin = $req->jardin ? 1 : 0;
            $ad->jardin_surface = $req->surfaceJardin ?? $ad->jardin_surface;
            if (isset($req->bg_image[0])) $ad->bg_image = $req->bg_image[0]['id'] ?? $ad->bg_image;
            $ad->vr_link = $req->vr_link ?? $ad->vr_link;
            $ad->status = $status ?? $ad->status;
            $ad->groundfloor = $req->groundfloor ? 1 : 0;
            $ad->gardenlevel = $req->gardenlevel ? 1 : 0;
            $ad->balcony = $req->balcony ? true : false;
            $ad->green_spaces = $req->green_spaces ? true : false;
            $ad->guardian = $req->guardian ? true : false;
            $ad->automation = $req->automation ? true : false;
            $ad->sea_view = $req->sea_view ? true : false;
            $ad->box = $req->box ? true : false;
            $ad->equipped_kitchen = $req->equipped_kitchen ? true : false;
            $ad->soundproof = $req->soundproof ? true : false;
            $ad->thermalinsulation = $req->thermalinsulation ? true : false;
            $ad->playground = $req->playground ? true : false;
            $ad->gym = $req->gym ? true : false;
            $ad->Chimney = $req->Chimney ? true : false;
            $ad->sportterrains = $req->sportterrains ? true : false;
            $ad->library = $req->library ? true : false;
            $ad->double_orientation = $req->double_orientation ? true : false;
            $ad->intercom = $req->intercom ? true : false;
            $ad->garage = $req->garage ? true : false;
            $ad->double_glazing = $req->double_glazing ? true : false;
            $ad->expiredate = $finalTime ?? $ad->expiredate;
            $ad->keywords = $keywords;

            $check = $ad->save();

            $images = $req->images;
            $videos = $req->videos;
            $audios = $req->audios;
            $nearbyplaces = $req->nearbyPlaces;

            if (!$check) {
                DB::rollBack();
                return $this->errorResponse('Something wrong!', 409);
            } else {
                ad_media::where('ad_id', "=", $ad->id)->delete();
                nearby_places::where('id_ad', "=", $ad->id)->delete();
                $i = 0;
                if (is_array($images)) foreach ($images as $key => $value) {
                    if ($max_ad_img == $key + 2) break;
                    try {
                        $media = new ad_media();
                        $media->ad_id = $ad->id;
                        $media->media_id = $value['id'];
                        $media->order = $i++;
                        $media->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $this->errorResponse('Somthing wrong!', 409);
                    }
                }
                $i = 0;
                if (is_array($videos)) foreach ($videos as $key => $value) {
                    if ($max_ad_video == $key + 2) break;
                    try {
                        $media = new ad_media();
                        $media->ad_id = $ad->id;
                        $media->media_id = $value['id'];
                        $media->order = $i++;
                        $media->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $this->errorResponse('Somthing wrong!', 409);
                    }
                }
                $i = 0;
                if (is_array($audios)) foreach ($audios as $key => $value) {
                    if ($max_ad_audio == $key + 2) break;
                    try {
                        $media = new ad_media();
                        $media->ad_id = $ad->id;
                        $media->media_id = $value['id'];
                        $media->order = $i++;
                        $media->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $this->errorResponse('Somthing wrong!', 409);
                    }
                }
                if (is_array($nearbyplaces)) foreach ($nearbyplaces as $key => $nearbyplace) {
                    try {
                        $nearby_places = new nearby_places();
                        $nearby_places->id_ad = $ad->id;
                        $nearby_places->title = $nearbyplace['title'];
                        $nearby_places->distance = $nearbyplace['distance'];
                        $nearby_places->id_place_type = $nearbyplace['type'] ?? $nearbyplace['types'];
                        $nearby_places->lat = $nearbyplace['lat'];
                        $nearby_places->lng = $nearbyplace['long'];
                        $nearby_places->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $this->errorResponse('Somthing wrong! ', 409);
                    }
                }
                DB::commit();
                $newData = ads::select('ads.id', 'ads.title', 'cats.title as category', DB::raw("COUNT(ad_media.ad_id) as nbr_media"), 'neighborhoods.name as district', 'ads.locdept2 as district2', 'cities.name as city', 'users.username as user', 'ads.created_at', 'ads.updated_at', 'ads.expiredate', 'ads.status')
                    ->join('cats', 'ads.catid', '=', 'cats.id')
                    ->leftjoin('ad_media', 'ads.id', '=', 'ad_media.ad_id')
                    ->leftjoin('neighborhoods', 'ads.locdept', '=', 'neighborhoods.id')
                    ->leftjoin('cities', 'ads.loccity', '=', 'cities.id')
                    ->join('users', 'ads.id_user', '=', 'users.id')
                    ->where('ads.id', '=', $ad->id)
                    ->first();

                return $this->showAny($newData);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function updateStatus(Request $req)
    {

        try {

            $status = $req->status;
            $id = $req->id;
            $ad = ads::find($id);
            // if not exist
            if (!$ad) {
                return $this->errorResponse("L'annonce est introuvable", 404);
            }

            $oldStatus = $ad->status;

            if ($status == '10') {
                $userid = $ad->userid;
                $max_user_ads = 0;
                $settings = settings::first();
                if ($settings) {
                    $max_user_ads = $settings->max_user_ads;
                } else {
                    return $this->errorResponse('Something wrong!', 404);
                }

                $contract = $this->getContractDataByUser($userid);
                if ($contract) {
                    $max_user_ads = $contract->ads_nbr;
                }

                $adsCount = ads::where('id_user', '=', $userid)->where('status', '=', '10')->count();
                if ($adsCount >= $max_user_ads) {
                    return $this->errorResponse('You have exceeded ad limits!', 409);
                }

                $EmailData = ([
                    'title' => $ad->title,
                    'price' => $ad->price
                ]);

                // Get the user id
                $userid = $ad->id_user;
                // Get the user mail and username
                $Usermail = User::select('email')->where('id', '=', $userid)->first();
                $username = User::select('username')->where('id', '=', $userid)->first();

                // get the ad city
                $adCity = cities::select('name')->where('id', '=', $ad->loccity)->first();

                // get the ad image
                $image = media::select(DB::raw("CONCAT(media.path,media.filename,'.',media.extension) as img"))
                    ->join('ad_media', 'ad_media.media_id', '=', 'media.id')
                    ->leftjoin('ads', 'ad_media.ad_id', '=', 'ads.id')
                    ->where('ads.id', '=', $ad->id)
                    ->first();

                // Host name
                $host_name = $_SERVER['HTTP_HOST'];

                // in case there is no image
                if (!$image) {
                    $img =  "/assets/img/no-photo.png";
                } else {
                    $img = $image->img;
                }

                // Mail::send(new SimpleMail(
                //     [
                //         "to" => $Usermail['email'],
                //         "subject" => "Votre annonce  est désormais publique !",
                //         "view" => "emails.AdPublished.fr",
                //         "data" => [
                //             "title" => $EmailData['title'],
                //             "username" => $username['username'],
                //             "price" => $EmailData['price'],
                //             "city" => $adCity['name'],
                //             "image" => $img,
                //             "id" => $ad->id,
                //             "host_name" => $host_name
                //         ]
                //     ]
                // ));
            }
            $ad->status = $status ?? $ad->status;
            $ad->moddate = Carbon::now();
            $check = $ad->save();

            if ($check) {

                (new LogActivity())->addToLog($id, $req);

                $data = [
                    'name' => auth()->user()->name,
                    'uid' => auth()->user()->id,
                    'body' => auth()->user()->username . " a modifié l'annonce: " . $ad->id,
                    'text' => "Visualiser l'annonce",
                    'url' => url('../item/' . $req->id),
                    'subject_id' => $req->id,
                    'notification_flag' => 'success'
                ];

                // Get the ad title
                $EmailData = ([
                    'title' => $ad->title,
                ]);

                // Get the user id
                $userid = $ad->id_user;
                // Get the user mail and username
                $Usermail = User::select('email')->where('id', '=', $userid)->first();
                $username = User::select('username')->where('id', '=', $userid)->first();

                if ($ad->status == 20) {
                    $data['body'] = auth()->user()->username . " a changé le statut de l'annonce: " . $ad->id . " En brouillon";;
                    $data['notification_flag'] = 'secondary';
                    $data['url'] = '/admin/items';

                    // get the ad city
                    $adCity = cities::select('name')->where('id', '=', $ad->loccity)->first();

                    // get the ad image
                    $image = media::select(DB::raw("CONCAT(media.path,media.filename,'.',media.extension) as img"))
                        ->join('ad_media', 'ad_media.media_id', '=', 'media.id')
                        ->leftjoin('ads', 'ad_media.ad_id', '=', 'ads.id')
                        ->where('ads.id', '=', $ad->id)
                        ->first();

                    // Host name
                    $host_name = $_SERVER['HTTP_HOST'];

                    // in case there is no image
                    if (!$image) {
                        $img ="/assets/img/no-photo.png";
                    } else {
                        $img = $image->img;
                    }

                    Mail::send(new SimpleMail(
                        [
                            "to" => $Usermail['email'],
                            "subject" => "Votre annonce est mise en brouillon !",
                            "view" => "emails.AdDraft.fr",
                            "data" => [
                                "title" => $EmailData["title"],
                                "username" => $username['username'],
                                "price" => $ad->price,
                                "city" => $adCity['name'],
                                "image" => $img,
                                "id" => $ad->id,
                                "host_name" => $host_name
                            ]
                        ]
                    ));
                }


                if ($ad->status == 30) {
                    $data['body'] = auth()->user()->username . " a changé le statut de l'annonce: " . $ad->id . " En revue";
                    $data['notification_flag'] = 'info';
                    $data['text'] = "Visualiser l'annonce";
                    $data['url'] = '../items/' . $ad->id;

                    // get the ad city
                    $adCity = cities::select('name')->where('id', '=', $ad->loccity)->first();

                    // get the ad image
                    $image = media::select(DB::raw("CONCAT(media.path,media.filename,'.',media.extension) as img"))
                        ->join('ad_media', 'ad_media.media_id', '=', 'media.id')
                        ->leftjoin('ads', 'ad_media.ad_id', '=', 'ads.id')
                        ->where('ads.id', '=', $ad->id)
                        ->first();

                    // Host name
                    $host_name = $_SERVER['HTTP_HOST'];

                    // in case there is no image
                    if (!$image) {
                        $img = "/assets/img/no-photo.png";
                    } else {
                        $img = $image->img;
                    }

                    Mail::send(new SimpleMail(
                        [
                            "to" => $Usermail['email'],
                            "subject" => "Votre annonce  est en revue !",
                            "view" => "emails.AdReview.fr",
                            "data" => [
                                "title" => $EmailData["title"],
                                "username" => $username['username'],
                                "price" => $ad->price,
                                "city" => $adCity['name'],
                                "image" => $img,
                                "id" => $ad->id,
                                "host_name" => $host_name
                            ]
                        ]
                    ));
                }

                if ($ad->status == 40) {
                    $data['body'] = auth()->user()->username . " a changé le statut de l'annonce: " . $ad->id . " En attente de paiment";
                    $data['notification_flag'] = 'alert';
                    $data['text'] = "Visualiser l'annonce";
                    $data['url'] = '../items/' . $ad->id;

                    // Get th ad city
                    $adCity = cities::select('name')->where('id', '=', $ad->loccity)->first();

                    // get the ad image
                    $image = media::select(DB::raw("CONCAT(media.path,media.filename,'.',media.extension) as img"))
                        ->join('ad_media', 'ad_media.media_id', '=', 'media.id')
                        ->leftjoin('ads', 'ad_media.ad_id', '=', 'ads.id')
                        ->where('ads.id', '=', $ad->id)
                        ->first();

                    // Host name
                    $host_name = $_SERVER['HTTP_HOST'];

                    // in case there is no image
                    if (!$image) {
                        $img = "/assets/img/no-photo.png";
                    } else {
                        $img = $image->img;
                    }

                    Mail::send(new SimpleMail(
                        [
                            "to" => $Usermail['email'],
                            "subject" => "Votre annonce  est en attente de paiement !",
                            "view" => "emails.AdPending.fr",
                            "data" => [
                                "title" => $EmailData["title"],
                                "username" => $username['username'],
                                "price" => $ad->price,
                                "city" => $adCity['name'],
                                "image" => $img,
                                "id" => $ad->id,
                                "host_name" => $host_name
                            ]
                        ]
                    ));
                }

                if ($ad->status == 50) {
                    $data['body'] = auth()->user()->username . " a changé le statut de l'annonce: " . $ad->id . " Rejetée";
                    $data['notification_flag'] = 'danger';

                    // get the ad city
                    $adCity = cities::select('name')->where('id', '=', $ad->loccity)->first();

                    // get the ad image
                    $image = media::select(DB::raw("CONCAT(media.path,media.filename,'.',media.extension) as img"))
                        ->join('ad_media', 'ad_media.media_id', '=', 'media.id')
                        ->leftjoin('ads', 'ad_media.ad_id', '=', 'ads.id')
                        ->where('ads.id', '=', $ad->id)
                        ->first();

                    // Host name
                    $host_name = $_SERVER['HTTP_HOST'];

                    // in case there is no image
                    if (!$image) {
                        $img = "/assets/img/no-photo.png";
                    } else {
                        $img = $image->img;
                    }

                    Mail::send(new SimpleMail(
                        [
                            "to" => $Usermail['email'],
                            "subject" => "Votre annonce est rejetée !",
                            "view" => "emails.AdRejected.fr",
                            "data" => [
                                "title" => $EmailData["title"],
                                "username" => $username['username'],
                                "price" => $ad->price,
                                "city" => $adCity['name'],
                                "image" => $img,
                                "id" => $ad->id,
                                "host_name" => $host_name

                            ]
                        ]
                    ));
                }

                if ($ad->status == 60) {
                    $data['body'] = auth()->user()->username . " a changé le statut de l'annonce: " . $ad->id . " Expirée";
                    $data['notification_flag'] = 'danger';

                    // get the ad city
                    $adCity = cities::select('name')->where('id', '=', $ad->loccity)->first();

                    // get the ad image
                    $image = media::select(DB::raw("CONCAT(media.path,media.filename,'.',media.extension) as img"))
                        ->join('ad_media', 'ad_media.media_id', '=', 'media.id')
                        ->leftjoin('ads', 'ad_media.ad_id', '=', 'ads.id')
                        ->where('ads.id', '=', $ad->id)
                        ->first();

                    // Host name
                    $host_name = $_SERVER['HTTP_HOST'];

                    // in case there is no image
                    if (!$image) {
                        $img = "/assets/img/no-photo.png";
                    } else {
                        $img = $image->img;
                    }

                    Mail::send(new SimpleMail(
                        [
                            "to" => $Usermail['email'],
                            "subject" => "Votre annonce  est expirée !",
                            "view" => "emails.AdExpired.fr",
                            "data" => [
                                "title" => $EmailData["title"],
                                "username" => $username['username'],
                                "price" => $ad->price,
                                "city" => $adCity['name'],
                                "image" => $img,
                                "id" => $ad->id,
                                "host_name" => $host_name,
                                "temps" => $ad->expiredate
                            ]
                        ]
                    ));
                }

                if ($ad->status == 70) {
                    $data['body'] = auth()->user()->username . " a supprimé l'annonce: " . $ad->id;
                    $data['notification_flag'] = 'danger';

                    // get the ad city
                    $adCity = cities::select('name')->where('id', '=', $ad->loccity)->first();

                    // get the ad image
                    $image = media::select(DB::raw("CONCAT(media.path,media.filename,'.',media.extension) as img"))
                        ->join('ad_media', 'ad_media.media_id', '=', 'media.id')
                        ->leftjoin('ads', 'ad_media.ad_id', '=', 'ads.id')
                        ->where('ads.id', '=', $ad->id)
                        ->first();

                    // Host name
                    $host_name = $_SERVER['HTTP_HOST'];

                    // in case there is no image
                    if (!$image) {
                        $img = "/assets/img/no-photo.png";
                    } else {
                        $img = $image->img;
                    }

                    Mail::send(new SimpleMail(
                        [
                            "to" => $Usermail['email'],
                            "subject" => "Votre annonce  est supprimée !",
                            "view" => "emails.AdDeleted.fr",
                            "data" => [
                                "title" => $EmailData["title"],
                                "username" => $username['username'],
                                "price" => $ad->price,
                                "city" => $adCity['name'],
                                "image" => $img,
                                "id" => $ad->id,
                                "host_name" => $host_name
                            ]
                        ]
                    ));
                }

                Notification::send(auth()->user(), new AnnounceNotifications($data));
            }

            if (!$check) {
                return $this->errorResponse('Something wrong!', 409);
            } else {
                $result = array('id' => $id, 'status' => $status);
                return $this->showAny($result);
            }
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function updateOptions(Request $req)
    {
        try {
            $option = $req->option;
            $id = $req->id;
            // get ad
            $ad = ads::find($id);
            $option_obj = options_catalogue::find($option);


            // if not exist
            if (!$ad)
                return $this->errorResponse('Ad not found', 404);

            // if not exist
            if (!$option_obj)
                return $this->errorResponse('Option not found', 404);

            // if this option is already applied to this ad
            //$optionObj = options::select('options.option_id')->join('options_catalogue','options.option_id', '=', 'options_catalogue.id')->where("options.ad_id","=",$req->id)->where('options.status','=','10')->where(DB::raw("TIMESTAMPADD(DAY,options_catalogue.duration ,options.timestamp)",">",DB::raw('CURRENT_TIMESTAMP')))->orderBy('options.id', 'DESC')->first();
            $optionObj = DB::select("SELECT o.option_id FROM `options` o inner join `options_catalogue` oc on o.option_id = oc.id where o.ad_id = $id and o.status = '10' and TIMESTAMPADD(DAY,oc.duration ,o.timestamp) > CURRENT_TIMESTAMP order by o.id desc");
            $optionObj = $optionObj[0] ?? null;
            if ($optionObj && $optionObj->option_id == $option)
                return $this->errorResponse('This option is already applied to this ad', 400);

            //if This ad is Unpublished
            $status = $ad->status;
            if ($status != '10')
                return $this->errorResponse('Cette annonce n\'est pas publiée', 404);

            $coinsManager = new  \App\Lib\CoinsManager;

            $result = $coinsManager->transaction2(
                $ad->id_user,
                - ($option_obj->price + 0),
                'boost_ad',
                "booster l'annonce(id:$id) par " . $option_obj->designation,
                function ($transaction) use ($option, $id, $req) {

                    DB::beginTransaction();

                    // disabled all previous options
                    options::where("ad_id", "=", $id)->where('status', '=', '10')->update([
                        'status' => '00'
                    ]);

                    //save the new option
                    $opt = new options();
                    $opt->option_id = $option;
                    $opt->ad_id = $id;
                    $opt->status = '10';
                    $opt->timestamp = Carbon::now();
                    $check = $opt->save();




                    if ($check) {
                        (new LogActivity())->addToLog($opt->ad_id, $req);

                        Notification::send(auth()->user(), new AnnounceNotifications([
                            'name' => auth()->user()->name,
                            'body' => "Annonce : " . $opt->ad_id . " est boostée par : " . auth()->user()->username,
                            'text' => "Visualiser l'annonce",
                            'url' => url('../item/' . $opt->ad_id),
                            'subject_id' => $opt->ad_id,
                            'notification_flag' => 'success',

                        ]));

                        $option = $req->option;
                        $id = $req->id;
                        // get ad
                        $ad = ads::find($id);
                        $option_obj = options_catalogue::find($option);

                        // Get the ad title
                        $EmailData = ([
                            'title' => $ad->title,
                            'designation' => $option_obj->designation
                        ]);

                        // Get the user id
                        $userid = $ad->id_user;
                        // Get the user mail and username
                        $Userinfo = User::select('email', 'username', 'coins')->where('id', '=', $userid)->first();

                        // get the ad city
                        $adCity = cities::select('name')->where('id', '=', $ad->loccity)->first();

                        // get the ad image
                        $image = media::select(DB::raw("CONCAT(media.path,media.filename,'.',media.extension) as img"))
                            ->join('ad_media', 'ad_media.media_id', '=', 'media.id')
                            ->leftjoin('ads', 'ad_media.ad_id', '=', 'ads.id')
                            ->where('ads.id', '=', $ad->id)
                            ->first();

                        // Host name
                        $host_name = $_SERVER['HTTP_HOST'];

                        // in case there is no image
                        if (!$image) {
                            $img =  "/assets/img/no-photo.png";
                        } else {
                            $img = $image->img;
                        }

                        Mail::send(new SimpleMail(
                            [
                                "to" => $Userinfo['email'],
                                "subject" => "Votre annonce  est boostée !!",
                                "view" => "emails.AdBoosted.fr",
                                "data" => [
                                    "title" => $EmailData["title"],
                                    "username" => Str::upper($Userinfo['lastname'] . "  " . $Userinfo['firstname']),
                                    "designation" => $EmailData['designation'],
                                    "coins" => $Userinfo['coins'],
                                    "city" => $adCity['name'],
                                    "image" => $img,
                                    "host_name" => $host_name
                                ]
                            ]
                        ));
                    }

                    if (!$check) {
                        DB::rollBack();
                        return false;
                    } else {
                        DB::commit();
                        return true;
                    }
                }
            );

            if ($result['success'] == true) {
                return $this->showAny(null);
            } else {
                return $this->errorResponse($result['message'], $result['code']);
            }
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function getAdOption(Request $req)
    {
        try {
            if (!isset($req->id)) return ['success' => false, 'error' => '', 'devError' => 'Param Id not found'];
            //$optionObj = DB::select("SELECT o.option_id FROM `options` o inner join `options_catalogue` oc on o.option_id = oc.id where o.ad_id = $req->id and o.status = '10' and TIMESTAMPADD(DAY,oc.duration ,o.timestamp) > CURRENT_TIMESTAMP order by o.id desc");
            $optionObj = DB::select('CALL optionsObj(?)', array($req->id));
            $optionObj = $optionObj[0] ?? null;
            //$optionObj = options::select('options.option_id')->join('options_catalogue','options.option_id', '=', 'options_catalogue.id')->where("options.ad_id","=",$req->id)->where('options.status','=','10')->where(DB::raw("TIMESTAMPADD(DAY,options_catalogue.duration ,options.timestamp)",">",DB::raw('CURRENT_TIMESTAMP')))->orderBy('options.id', 'DESC')->first();
            $option = null;
            if ($optionObj) $option = $optionObj->option_id ?? null;
            return $this->showAny($option);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    private function getContractDataByUser($id)
    {
        if ($id) {
            $data = Contract::where('user_id', '=', $id)->where('active', '=', '1')->orderBy('id', 'DESC')->first();
            return $data;
        }
        return null;
    }

    /**
     * release
     */
    function loadDeptsByCity(Request $request)
    {
        $data = neighborhoods::select("neighborhoods.*", "cities.coordinates as dCoordinates")->join("cities", "neighborhoods.city_id", "=", "cities.id")
            ->where('cities.id', '=', $request->city)->groupBy('neighborhoods.id')->orderBy('neighborhoods.name')->get();
        return $this->showAny($data);
    }

    /**
     * LogsRectif
     */
    function getLatestActivities()
    {
        try{
            $user = auth()->user();
            if(!$user) return $this->errorResponse("user nnot found!", 403);
            $notifications = $user->notifications->take(5);
            // $lastestActivities = new Activities;
            // $lastestActivities = $lastestActivities->all();
            $lastestActivities = DB::table('log_activities')->limit(5)->get();

            foreach ($notifications as $n) {
                $n->ActivityType = 'notif';
                $n->date = carbon::parse($n->created_at)->diffForHumans();
                $n->username = User::where('id', '=', $n->notifiable_id);
            }

            foreach ($lastestActivities as $a) {
                $a->ActivityType = 'log';
                $a->date = carbon::parse($a->created_at)->diffForHumans();
                $a->user = User::where('id', '=', $a->user_id)->get();
            }

            $data = [...$notifications, ...$lastestActivities];
            // return collect($data)->sortByDesc('created_at')->all();
            $sorted = collect($data)->sortByDesc('created_at');
            return $sorted->values()->all();
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    function SyncAdsKeywords(Request $request)
    {
        $ads = ads::select('id', 'catid', 'loccity', 'locdept', 'locdept2', 'surface')->get();
        foreach ($ads as $ad) {
            $req = (object)[
                "catid" => $ad->catid,
                "loccity" => $ad->loccity,
                "locdept" => $ad->locdept,
                "locdept2" => $ad->locdept2,
                "surface" => $ad->surface,
            ];
            $keywords = makeAdKeyWords($req);
            try {
                ads::where('id', '=', $ad->id)->update([
                    "keywords" => $keywords
                ]);
            } catch (\Throwable $th) {
            }
        }
        return $this->showAny(null);
    }

    function SyncAdsKeywordsByCategory(Request $request)
    {
        $catid = $request->catid;
        $ads = ads::select('id', 'catid', 'loccity', 'locdept', 'locdept2', 'surface')->where('catid', '=', $catid)->get();
        foreach ($ads as $ad) {
            $req = (object)[
                "catid" => $ad->catid,
                "loccity" => $ad->loccity,
                "locdept" => $ad->locdept,
                "locdept2" => $ad->locdept2,
                "surface" => $ad->surface,
            ];
            $keywords = makeAdKeyWords($req);
            try {
                ads::where('id', '=', $ad->id)->update([
                    "keywords" => $keywords
                ]);
            } catch (\Throwable $th) {
            }
        }
        return $this->showAny(null);
    }
}
