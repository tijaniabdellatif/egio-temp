<?php

namespace App\Http\Controllers\api;

use App\Models\ads;
use App\Models\cats;
use App\Models\User;
use App\Models\media;
use App\Models\cities;
use App\Mail\SimpleMail;
use App\Models\ad_media;
use App\Models\Contract;
use App\Models\settings;
use App\Mail\AdPublished;
use App\Models\places_type;
use App\Helpers\LogActivity;
use App\Models\UserContacts;
use Illuminate\Http\Request;
use App\Models\nearby_places;
use App\Models\neighborhoods;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Notifications\AnnounceNotifications;
use Illuminate\Support\Facades\Notification;

class AddAdController extends ApiController
{
    // Get User Contacts
    function getUserContacts(Request $request)
    {
        $id = $request->user_id;
        $userPhones = [];
        $userWtsps = [];
        $userEmails = [];
        if ($id) {
            $user = User::select('phone', 'email')->where('id', '=', $id)->first();
            $userContacts = UserContacts::where('user_id', '=', $id)->get();

            if ($user) {
                if ($user->phone) $userPhones[] = (object)["id" => -1, "value" => $user->phone];
                if ($user->email) $userEmails[] = (object)["id" => -1, "value" => $user->email];
                foreach ($userContacts as $key => $value) {
                    if ($value->type == "phone") $userPhones[] = (object)["id" => $value->id, "value" => $value->value];
                    if ($value->type == "whatsapp") $userWtsps[] = (object)["id" => $value->id, "value" => $value->value];
                    if ($value->type == "email") $userEmails[] = (object)["id" => $value->id, "value" => $value->value];
                }
            }
        }

        return $this->showAny([
            "success" => true,
            "userphones" => $userPhones,
            "userWtsps" => $userWtsps,
            "userEmails" => $userEmails
        ]);
    }

    public function addAd(Request $req)
    {

        try {

            $validatorArr = [
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

            if ($req->is_project == true) {
                $validatorArr['surface2'] = 'nullable|integer';
            }

            if ($req->is_child == true) {
                $validatorArr['parent_project'] = 'required|integer';
            }

            if ($req->locdept == '-1') $validatorArr['locdept'] = 'nullable|integer';

            // validate request data
            $validator = Validator::make($req->all(), $validatorArr);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }


            $status = $req->status;
            if ($status != '30' && $status != '20') {
                return $this->errorResponse("status must be 20 or 30", 400);
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



            $contract = $this->getContractDataByUser($req->user_id);
            if ($contract) {
                $max_user_ads = $contract->ads_nbr;
                $contract_date = new Carbon($contract->date);
                $finalTime = $contract_date->addDays($contract->duration);
            }

            $adsCount = ads::where('id_user', '=', $req->user_id)->where('status', '=', '10')->count();

            if ($adsCount >= $max_user_ads && $status == '30') {
                return $this->errorResponse('Vous avez dépassé votre limite d\'annonces!', 409);
            }

            $keywords = makeAdKeyWords($req);

            DB::beginTransaction();

            $ad = new ads();
            $ad->title = $req->title;
            $ad->description = $req->description;
            $ad->catid = $req->catid;
            $ad->price = $req->price;
            $ad->price_curr = $req->price_curr;
            $ad->price_surface = $req->price_m;
            $ad->loclng = $req->long;
            $ad->loclat = $req->lat;
            $ad->loccity = $req->loccity;
            if ($req->locdept != '-1') $ad->locdept = $req->locdept;
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
            $ad->is_project = $req->is_project ? 1 : 0;
            $ad->parent_project = $req->parent_project ?? null;
            $ad->built_year = $req->contriction_date;
            $ad->etage = $req->etage;
            $ad->etage_total = $req->etage_total;
            $ad->price_surface = $req->surface && $req->price ? ($req->price / $req->surface) : null;
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
            $ad->groundfloor = $req->groundfloor ? true : false;
            $ad->gardenlevel = $req->gardenlevel ? true : false;
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
            $ad->expiredate = $finalTime;
            $ad->keywords = $keywords;




            $check = $ad->save();


            if ($check) {

                $notifMessage = '';
                if ($status == '20') {
                    $notifMessage = 'Votre annonces est mise en brouillon.';
                } else if ($status == '30') {
                    $notifMessage = 'Votre annonce a été ajoutée .';
                }

                if (getCurrentUser()) {

                    // Add to logs
                    (new LogActivity())->addToLog($ad->id, $req);

                    Notification::send(User::first(), new AnnounceNotifications([
                        'name' => getCurrentUser()->username,
                        'body' => $notifMessage,
                        'text' => 'Vérifier l\'annonce',
                        'url' => url('/admin/items'),
                        'subject_id' => $ad->id,
                        'notification_flag' => 'info'
                    ]));
                }
            }

            $images = $req->images;
            $videos = $req->videos;
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
                if (is_array($nearbyplaces)) foreach ($nearbyplaces as $key => $nearbyplace) {
                    try {
                        $nearby_places = new nearby_places();
                        $nearby_places->id_ad = $ad->id;
                        $nearby_places->title = $nearbyplace['title'];
                        $nearby_places->distance = $nearbyplace['distance'];
                        $nearby_places->id_place_type = $nearbyplace['types'];
                        $nearby_places->save();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return $this->errorResponse('Something wrong!', 409);
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
                        }
                        else{
                            $img = $image->img;
                        }

                        //Send mail when user add announce
                        Mail::send(new SimpleMail(
                            [
                                "to" => getCurrentUser()->email,
                                "subject" => "Votre annonce  est en revue !",
                                "view" => "emails.AdReview.fr",
                                "data" => [
                                    "id" => $ad->id,
                                    "title" => $ad->title,
                                    "username" => getCurrentUser()->username,
                                    "price" => $ad->price,
                                    "city" => $adCity['name'],
                                    "image" => $img,
                                    "host_name" => $host_name
                                ]
                            ]
                        ));
                    }

                    if (getCurrentUser()) {

                        // Add to logs
                        (new LogActivity())->addToLog($ad->id, $req);

                        Notification::send(User::first(), new AnnounceNotifications([
                            'name' => getCurrentUser()->username,
                            'body' => $notifMessage,
                            'text' => 'Vérifier l\'annonce',
                            'url' => url('/admin/items'),
                            'subject_id' => $ad->id,
                            'notification_flag' => 'info'
                        ]));
                    }
                }


                return $this->showAny($ad->id);
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

    function loadDeptsByCity(Request $request)
    {
        $data = neighborhoods::select("neighborhoods.id","neighborhoods.name")
            ->where('neighborhoods.city_id', '=', $request->city)->orderBy('neighborhoods.name')->get();
        return $this->showAny($data);
    }

    function loadDeptCoordinates(Request $request)
    {
        $data = neighborhoods::select("neighborhoods.*", "cities.coordinates as dCoordinates")->join("cities", "neighborhoods.city_id", "=", "cities.id")
            ->where('neighborhoods.id', '=', $request->dept)->first();
        return $this->showAny($data);
    }
}
