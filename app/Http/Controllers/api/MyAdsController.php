<?php

namespace App\Http\Controllers\api;

use App\Models\ads;
use App\Models\User;
use App\Models\media;
use App\Models\cities;
use App\Models\options;
use App\Mail\SimpleMail;
use App\Models\ad_media;
use App\Models\Contract;
use App\Models\settings;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Models\nearby_places;
use Illuminate\Support\Carbon;
use App\Models\options_catalogue;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Notifications\AnnounceNotifications;
use Illuminate\Support\Facades\Notification;

class MyAdsController extends ApiController
{

    public function myAds(Request $request)
    {

        try {
            $cost = 10;
            $validator = Validator::make($request->all(), [
                'from' => 'required|integer',
                'count' => 'required|integer',
                'id' => 'required|integer',
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }
            $filter = "";
            $order = " ORDER BY a.published_at DESC ";
            if ($request->type) {
                $filter .= " AND c.type = '$request->type' ";
            }
            if ($request->categorie) {
                $filter .= " AND c.id = '$request->categorie' ";
            }
            if ($request->univer) {
                $filter .= " AND c.parent_cat = '$request->univer' ";
            }
            if ($request->standing) {
                $filter .= " AND c.standing = '$request->standing' ";
            }
            if ($request->region) {
                $filter .= " AND p.region_id = '$request->region' ";
            }
            if ($request->city) {
                $filter .= " AND a.loccity = '$request->city' ";
            }
            if ($request->neighborhood) {
                $filter .= " AND a.locdept = '$request->neighborhood' ";
            }
            if ($request->min_price) {
                $min_price = $request->min_price;
                $min_price_eur = ($min_price * 1) / $cost;
                $filter .= " AND ((a.price >= '$min_price' and a.price_curr <> 'EUR') or (a.price >= '$min_price_eur' and a.price_curr = 'EUR')) ";
            }
            if ($request->max_price) {
                $max_price = $request->max_price;
                $max_price_eur = ($max_price * 1) / $cost;
                $filter .= " AND ((a.price <= '$max_price' and a.price_curr <> 'EUR') or (a.price <= '$max_price_eur' and a.price_curr = 'EUR')) ";
            }
            if ($request->min_surface) {
                $filter .= " AND a.surface >= '$request->min_surface' ";
            }
            if ($request->max_surface) {
                $filter .= " AND a.surface <= '$request->max_surface' ";
            }
            if ($request->rooms) {
                $filter .= " AND a.rooms = '$request->rooms' ";
            }
            if ($request->bedrooms) {
                $filter .= " AND a.bedrooms = '$request->bedrooms' ";
            }
            if ($request->bathrooms) {
                $filter .= " AND a.bathrooms = '$request->bathrooms' ";
            }
            if ($request->age) {
                $filter .= " AND a.built_year = $request->age ";
            }
            if ($request->jardin == true) {
                $filter .= " AND (a.jardin <> '0' and a.jardin is not null) ";
            }
            if ($request->piscine == true) {
                $filter .= " AND (a.piscine <> '0' and a.piscine is not null) ";
            }
            if ($request->parking == true) {
                $filter .= " AND (a.parking <> '0' and a.parking is not null) ";
            }
            if ($request->meuble == true) {
                $filter .= " AND (a.meuble <> '0' and a.meuble is not null) ";
            }
            if ($request->security == true) {
                $filter .= " AND (a.securite <> '0' and a.securite is not null) ";
            }
            if ($request->clime == true) {
                $filter .= " AND (a.climatise <> '0' and a.climatise is not null) ";
            }
            if ($request->terrasse == true) {
                $filter .= " AND (a.terrace <> '0' and a.terrace is not null) ";
            }
            if ($request->cave == true) {
                $filter .= " AND (a.cave <> '0' and a.cave is not null) ";
            }
            if ($request->syndic == true) {
                $filter .= " AND (a.syndic <> '0' and a.syndic is not null) ";
            }
            if ($request->ascenseur == true) {
                $filter .= " AND (a.ascenseur <> '0' and a.ascenseur is not null) ";
            }
            if ($request->status) {
                $filter .= " AND a.status = '$request->status' ";
            }
            if ($request->search && trim($request->search)) {
                $search = strtolower(trim($request->search));
                $search2arr = explode(' ', $search);
                $search2 = "";
                foreach ($search2arr as $s) {
                    if ($s) {
                        if (strlen($s) > 2) $search2 .= '+' . trim($s);
                        else $search2 .= '+__' . trim($s);
                    }
                }
                $filter .= " AND
                    (LOWER(a.title) like '%$search%' or LOWER(a.locdept2) like '%$search%' or LOWER(a.ref) like '%$search%'
                    or LOWER(ct.name) like '%$search%' or LOWER(n.name) like '%$search%' or LOWER(u.username) like '%$search%'
                    or LOWER(c.title) like '%$search%' or MATCH (a.keywords,a.description) AGAINST('$search2' IN BOOLEAN MODE)
                ) ";
            }

            if ($request->sort == 'vues') {
                $order = " ORDER BY views DESC ";
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
            $list = DB::select(
                "SELECT a.id , a.title , a.price , a.description , a.price_curr , a.ref , a.surface ,a.`rooms` ,
                        a.bedrooms , a.bathrooms , a.loccity , a.locdept , a.locdept2 , ct.name as 'city' ,n.name as 'neighborhood' ,
                        a.id_user , u.username , u.usertype , a.catid , c.type , c.title as 'categorie' , c.parent_cat , a.published_at as 'date' ,
                        CONCAT(m.path,m.filename,'.',m.extension) AS avatar , a.status , IFNULL(vClicks.views,0) as 'views' ,
                        IFNULL(wClicks.wtsps,0) as 'wtsps' , IFNULL(pClicks.phones,0) as 'phones' , IFNULL(eClicks.emails,0) as 'emails'
                        , oc.designation as 'current_option' , TIMESTAMPADD(DAY,oc.duration ,o.timestamp) as 'option_expired_date'
                        FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                        LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id
                        LEFT JOIN user_info ui on u.id = ui.user_id LEFT JOIN media m on ui.avatar = m.id
                        LEFT JOIN
                            (select ad_id , COUNT(clicks.id) as 'views' from `clicks` where `type` = 'hit' group by ad_id) as vClicks
                            on a.id = vClicks.ad_id
                        LEFT JOIN
                            (select ad_id , COUNT(clicks.id) as 'wtsps' from `clicks` where `type` = 'wtsp' group by ad_id) as wClicks
                            on a.id = wClicks.ad_id
                        LEFT JOIN
                            (select ad_id , COUNT(clicks.id) as 'phones' from `clicks` where `type` = 'phone' group by ad_id) as pClicks
                            on a.id = pClicks.ad_id
                        LEFT JOIN
                            (select ad_id , COUNT(emails.id) as 'emails' from `emails` group by ad_id) as eClicks
                            on a.id = eClicks.ad_id
                        LEFT JOIN `options` o on o.ad_id = a.id
                        LEFT JOIN `options_catalogue` oc on o.option_id = oc.id and o.status = '10'
                        and TIMESTAMPADD(DAY,oc.duration ,o.timestamp) > CURRENT_TIMESTAMP
                        WHERE a.id_user = $request->id
                        $filter
                        $order limit $request->from , $request->count
            "
            );
            $total = ads::where('status','<>','70')->where('id_user','=',$request->id)->count();
            $dataTotal = DB::select(
                "SELECT COUNT(a.id) as 'count'
                        FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                        LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id
                        WHERE a.status <> '70' and a.expiredate > CURRENT_TIMESTAMP  and a.id_user = $request->id
                        $filter
            "
            );

            $searchTotal = $dataTotal[0] ? $dataTotal[0]->count : 0;
            $result = [];

            foreach ($list as $value) {
                $obj = $value;
                $obj->images = DB::select('CALL adsImages(?)', array($value->id));
                //$obj->videos = DB::select('CALL adsVideos(?)',array($value->id));
                //$obj->audios = DB::select('CALL adsAudios(?)',array($value->id));
                $obj->premium = false;
                $result[] = $obj;
            }

            return $this->showAny(["data" => $result, "searchTotal" => $searchTotal, "total" => $total]);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function boostAd(Request $req)
    {
        try {
            $option = $req->option;
            $id = $req->id;
            // get ad
            $ad = ads::find($id);
            $option_obj = options_catalogue::find($option);

            // if not exist
            if (!$ad)
                return $this->errorResponse('Annonce introvable!', 404);

            // if not exist
            if (!$option_obj)
                return $this->errorResponse("Cette option de boost n'est plus disponible", 404);

            // if this option is already applied to this ad
            //$optionObj = options::select('options.option_id')->join('options_catalogue','options.option_id', '=', 'options_catalogue.id')->where("options.ad_id","=",$req->id)->where('options.status','=','10')->where(DB::raw("TIMESTAMPADD(DAY,options_catalogue.duration ,options.timestamp)",">",DB::raw('CURRENT_TIMESTAMP')))->orderBy('options.id', 'DESC')->first();
            $optionObj = DB::select("SELECT o.option_id FROM `options` o inner join `options_catalogue` oc on o.option_id = oc.id where o.ad_id = $id and o.status = '10' and TIMESTAMPADD(DAY,oc.duration ,o.timestamp) > CURRENT_TIMESTAMP order by o.id desc");
            $optionObj = $optionObj[0] ?? null;
            if ($optionObj && $optionObj->option_id == $option)
                return $this->errorResponse('Cette option de boost est déjà appliqué sur cette annonce', 400);

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
                    }

                    if (!$check) {
                        DB::rollBack();
                        return false;
                    } else {
                        DB::commit();

                        $ad = ads::find($id);
                        $option_obj = options_catalogue::find($option);

                        // Get the ad title
                        $EmailData = ([
                            'title' => $ad->title,
                            'designation' => $option_obj->designation,
                            'prix' => $option_obj->price
                        ]);

                        // get the ad city
                        $adCity = cities::select('name')->where('id', '=', $ad->loccity)->first();

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

                        // Send mail when user update ad
                        Mail::send(new SimpleMail(
                            [
                                "to" => getCurrentUser()->email,
                                "subject" => "Votre annonce  est boosté !",
                                "view" => "emails.UserBoostAd.fr",
                                "data" => [
                                    "title" => $EmailData['title'],
                                    "username" => getCurrentUser()->username,
                                    "option" => $EmailData['designation'],
                                    "prix" => $EmailData['prix'],
                                    "solde" => getCurrentUser()->coins,
                                    "city" => $adCity['name'],
                                    "image" => $img,
                                    "host_name" => $host_name
                                ]
                            ]
                        ));
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
            return $this->errorResponse("erreur de serveur" . $th->getMessage(), 500);
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
                'price_m' => 'integer|nullable',
                'lat' => 'nullable|numeric',
                'long' => 'nullable|numeric',
                'loccity' => 'required|integer',
                'locdept' => 'required|integer',
                'locdept2' => 'nullable|string',
                'user_id' => 'required|integer',
                'phone' => 'required|integer',
                'phone2' => 'nullable|integer',
                'wtsp' => 'nullable|integer',
                'email' => 'required|integer',
                'rooms' => 'integer|nullable',
                'bedrooms' => 'integer|nullable',
                'wc' => 'integer|nullable',
                'surface' => 'integer|nullable',
                'contriction_date' => 'integer|nullable',
                'etage' => 'integer|nullable',
                'etage_total' => 'integer|nullable',
                'standing' => 'integer|nullable',
                'ref' => 'string|nullable',
                'status' => 'required|string',
                'images' => 'array',
                'videos' => 'array',
                'nearbyplaces' => 'array'
            ];

            if ($req->locdept == '-1') $validatorArr['locdept'] = 'nullable|integer';

            // validate request data
            $validator = Validator::make($req->all(), $validatorArr);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }


            $status = $req->status;
            if ($status != '30' && $status != '20' && $status != '70') {
                return $this->errorResponse("status must be 20 or 30 or 70", 400);
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
                return $this->errorResponse('Somthing wrong! settings not found', 404);
            }

            $contract = $this->getContractDataByUser($req->user_id);
            if ($contract) {
                $max_user_ads = $contract->ads_nbr;
                $contract_date = new Carbon($contract->date);
                $finalTime = $contract_date->addDays($contract->duration);
            }

            $adsCount = ads::where('id_user', '=', $req->user_id)->where('status', '=', '10')->count();

            if ($adsCount >= $max_user_ads && $status == '30') {
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
            $ad->description = $req->description;
            $ad->catid = $req->catid;
            $ad->price = $req->price;
            $ad->price_curr = $req->price_curr;
            $ad->price_surface = $req->price_m;
            $ad->loclng = $req->long;
            $ad->loclat = $req->lat;
            $ad->loccity = $req->loccity;
            if ($req->dept != '-1') $ad->locdept = $req->locdept;
            $ad->locdept2 = $req->locdept2;
            $ad->id_user = $req->user_id;
            $ad->phone = $req->phone;
            $ad->phone2 = $req->phone2;
            $ad->wtsp = $req->wtsp;
            $ad->email = $req->email;
            $ad->rooms = $req->rooms;
            $ad->bedrooms = $req->bedrooms;
            $ad->bathrooms = $req->wc;
            $ad->surface = $req->surface;
            $ad->surface2 = $req->surface2 ?? null;
            $ad->built_year = $req->contriction_date;
            $ad->etage = $req->etage;
            $ad->etage_total = $req->etage_total;
            $ad->standing = $req->standing;
            $ad->ref = $req->ref;
            $ad->meuble = $req->meuble ? true : false;
            $ad->climatise = $req->climatise ? true : false;
            $ad->jardin = $req->jardin ? true : false;
            $ad->piscine = $req->piscine ? true : false;
            $ad->parking = $req->parking ? true : false;
            $ad->terrace = $req->terrace ? true : false;
            $ad->syndic = $req->syndic ? true : false;
            $ad->cave = $req->cave ? true : false;
            $ad->ascenseur = $req->ascenseur ? true : false;
            $ad->securite = $req->securite ? true : false;
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
            $ad->status = $status;
            $ad->keywords = $keywords;
            //$ad->expiredate = $finalTime;

            $check = $ad->save();

            $images = $req->images;
            $videos = $req->videos;
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
                        return $this->errorResponse('Somthing wrong!', 409);
                    }
                }
                DB::commit();

                if ($check) {

                    $notifMessage = '';
                    if ($status == '20') {
                        $notifMessage = 'Votre annonce est mise en brouillon.';
                    } else if ($status == '30') {
                        $notifMessage = 'Votre annonce est passé en modération.';

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
                                "to" => getCurrentUser()->email,
                                "subject" => "Votre annonce est passée en modération !",
                                "view" => "emails.UserUpdateAd.fr",
                                "data" => [
                                    "title" => $ad->title,
                                    "username" => getCurrentUser()->username,
                                    "price" => $ad->price,
                                    "id" => $ad->id,
                                    "city" => $adCity['name'],
                                    "image" => $img,
                                    "host_name" => $host_name
                                ]
                            ]
                        ));
                    } else if ($status == '70') {
                        $notifMessage = 'Votre annonce a été supprimée.';
                    }

                    if (getCurrentUser()) {

                        (new LogActivity())->addToLog($ad->id, $req);
                        Notification::send(User::first(), new AnnounceNotifications([
                            'name' => getCurrentUser()->username,
                            'body' => $notifMessage,
                            'text' => 'Visualiser l\'annonce ',
                            'url' => url('/admin/items'),
                            'subject_id' => $ad->id,
                            'notification_flag' => 'info'
                        ]));
                    }
                }
                $newData = DB::select(
                    "SELECT a.id , a.title , a.price , a.description , a.price_curr , a.ref , a.surface ,a.`rooms` ,
                            a.bedrooms , a.bathrooms , a.loccity , a.locdept , a.locdept2 , ct.name as 'city' ,n.name as 'neighborhood' ,
                            a.id_user , u.username , u.usertype , a.catid , c.type , c.title as 'categorie' , c.parent_cat , a.published_at as 'date' ,
                            CONCAT(m.path,m.filename,'.',m.extension) AS avatar , a.status , IFNULL(vClicks.views,0) as 'views' ,
                            IFNULL(wClicks.wtsps,0) as 'wtsps' , IFNULL(pClicks.phones,0) as 'phones' , IFNULL(eClicks.emails,0) as 'emails'
                            FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                            LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id
                            LEFT JOIN user_info ui on u.id = ui.user_id LEFT JOIN media m on ui.avatar = m.id
                            LEFT JOIN
                                (select ad_id , COUNT(clicks.id) as 'views' from `clicks` where `type` = 'hit' group by ad_id) as vClicks
                                on a.id = vClicks.ad_id
                            LEFT JOIN
                                (select ad_id , COUNT(clicks.id) as 'wtsps' from `clicks` where `type` = 'wtsp' group by ad_id) as wClicks
                                on a.id = wClicks.ad_id
                            LEFT JOIN
                                (select ad_id , COUNT(clicks.id) as 'phones' from `clicks` where `type` = 'phone' group by ad_id) as pClicks
                                on a.id = pClicks.ad_id
                            LEFT JOIN
                                (select ad_id , COUNT(emails.id) as 'emails' from `emails` group by ad_id) as eClicks
                                on a.id = eClicks.ad_id
                            WHERE a.id = $ad->id
                "
                );
                if (isset($newData[0])) {
                    $newData[0]->images = DB::select('CALL adsImages(?)', array($newData[0]->id));
                    return $this->showAny($newData[0]);
                } else {
                    return $this->showAny(null);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
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

    public function deleteAd(Request $req)
    {
        try {
            if (getCurrentUser() == null) return $this->errorResponse('you re not logged in', 401);
            $id = $req->id;

            $ad = ads::find($id);
            // if not exist
            if (!$ad) {
                return $this->errorResponse('Ad not found', 404);
            }

            $ad->status = '70';
            $ad->moddate = Carbon::now();
            $check = $ad->save();

            if (!$check) {
                return $this->errorResponse('Something wrong!', 409);
            } else {
                $data['body'] = 'Votre annonce a été supprimée.';
                $data['notification_flag'] = 'danger';

                Notification::send(getCurrentUser(), new AnnounceNotifications([
                    'name' => getCurrentUser()->username,
                    'body' => 'Votre annonce a été supprimée.',
                    'text' => '',
                    'url' => url('/admin/items'),
                    'subject_id' => $ad->id,
                    'notification_flag' => 'danger',
                ]));

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

                // Send mail when user delete his announce
                Mail::send(new SimpleMail(
                    [
                        "to" => getCurrentUser()->email,
                        "subject" => "Votre annonce a été supprimée !",
                        "view" => "emails.UserDeleteAd.fr",
                        "data" => [
                            "title" => $ad->title,
                            "username" => getCurrentUser()->username,
                            "price" => $ad->price,
                            "city" => $adCity['name'],
                            "id" => $ad->id,
                            "image" => $img,
                            "host_name" => $host_name
                        ]
                    ]
                ));

                return $this->showAny($id);
            }
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }
}
