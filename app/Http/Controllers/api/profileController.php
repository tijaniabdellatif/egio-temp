<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ads;
use App\Models\User;
use App\Models\UserContact;
use App\Models\UserContacts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class profileController extends ApiController
{

    protected $proUsersTypes = [4, 3];

    public function profileAds(Request $request)
    {

        try {
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
            if ($request->status) {
                $filter .= " AND a.status = '$request->status' ";
            }
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
            if ($request->city) {
                $filter .= " AND a.loccity = '$request->city' ";
            }
            if ($request->neighborhood) {
                $filter .= " AND a.locdept = '$request->neighborhood' ";
            }
            if ($request->min_price) {
                $filter .= " AND a.price >= '$request->min_price' ";
            }
            if ($request->max_price) {
                $filter .= " AND a.price <= '$request->max_price' ";
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
                $filter .= " AND (a.built_year is not null AND $request->age = (year(curdate()) - a.built_year)) ";
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
            if ($request->search && trim($request->search)) {
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

            if ($request->orderBy == 'vues') {
                $order = " ORDER BY views DESC ";
            }
            $list = DB::select(
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
                        WHERE a.status <> '70' and a.expiredate > CURRENT_TIMESTAMP and a.id_user = $request->id
                        $filter
                        $order limit $request->from , $request->count
            "
            );
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

            return $this->showAny(["data" => $result, "searchTotal" => $searchTotal]);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function profileProjects(Request $request)
    {

        try {
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
            if ($request->status) {
                $filter .= " AND a.status = '$request->status' ";
            }
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
            if ($request->city) {
                $filter .= " AND a.loccity = '$request->city' ";
            }
            if ($request->neighborhood) {
                $filter .= " AND a.locdept = '$request->neighborhood' ";
            }
            if ($request->min_price) {
                $filter .= " AND a.price >= '$request->min_price' ";
            }
            if ($request->max_price) {
                $filter .= " AND a.price <= '$request->max_price' ";
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
                $filter .= " AND (a.built_year is not null AND $request->age = (year(curdate()) - a.built_year)) ";
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
            if ($request->status) {
                $filter .= " AND a.status = '$request->status' ";
            }
            if ($request->search && trim($request->search)) {
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

            if ($request->orderBy == 'vues') {
                $order = " ORDER BY views DESC ";
            }
            $list = DB::select(
                "SELECT a.id , a.title , a.price , a.loclat , a.loclng , a.description , a.price_curr , a.ref , a.surface , a.surface2 , a.`rooms` ,
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
                        WHERE a.status <> '70' and a.expiredate > CURRENT_TIMESTAMP and a.is_project = 1 and a.id_user = $request->id
                        $filter
                        $order limit $request->from , $request->count
            "
            );
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

            return $this->showAny(["data" => $result, "searchTotal" => $searchTotal]);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function profileProjectDispos(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }
            $list = DB::select(
                "SELECT a.id , a.title , a.price , a.description , a.price_curr , a.ref , a.surface , a.surface2 , a.`rooms` ,
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
                        WHERE a.status <> '70' and a.expiredate > CURRENT_TIMESTAMP and a.parent_project = $request->id
            "
            );

            $result = [];
            foreach ($list as $value) {
                $obj = $value;
                $obj->images = DB::select('CALL adsImages(?)', array($value->id));
                //$obj->videos = DB::select('CALL adsVideos(?)',array($value->id));
                //$obj->audios = DB::select('CALL adsAudios(?)',array($value->id));
                $obj->premium = false;
                $result[] = $obj;
            }

            return $this->showAny(["data" => $result]);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function profileData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);
            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }

            $data = User::select(
                "users.*",
                "pro_user_info.company",
                "user_type.designation as user_type",
                "user_info.bio",
                "user_info.likes",
                DB::raw("CONCAT(banner.path,banner.filename,'.',banner.extension) AS banner"),
                DB::raw("CONCAT(media.path,media.filename,'.',media.extension) AS avatar"),
                "pro_user_info.website"
            )
                ->leftJoin("user_info", "user_info.user_id", "=", "users.id")
                ->leftJoin('pro_user_info', "pro_user_info.user_id", "=", "users.id")
                ->leftJoin('media', 'media.id', '=', 'user_info.avatar')
                ->leftJoin('media as banner', 'banner.id', '=', 'pro_user_info.probannerimg')
                ->leftJoin('user_type', 'user_type.id', '=', 'users.usertype')
                ->where('users.id', "=", $request->id)->first();
            if ($data) {
                $userPhones = [];
                $userEmails = [];
                $userWtsps = [];
                $userContacts = UserContacts::where('user_id', '=', $data->id)->get();
                foreach ($userContacts as $key => $value) {
                    if ($value->type == "phone") {
                        $userPhones[] = $value->value;
                    }
                    if ($value->type == "whatsapp") {
                        $userWtsps[] = $value->value;
                    }
                    if ($value->type == "email") {
                        $userEmails[] = $value->value;
                    }
                }
                $data->userPhones = $userPhones;
                $data->userEmails = $userEmails;
                $data->userWtsps = $userWtsps;
            }
            return $this->showAny($data);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function editProfileData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);
            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }

            $data = User::select(
                "users.id",
                "users.usertype",
                "users.username",
                "users.phone",
                "users.firstname",
                "users.lastname",
                "users.email",
                "pro_user_info.company",
                "pro_user_info.website",
                "pro_user_info.city",
                "user_info.bio",
                "mAvatar.id as idAvatar",
                DB::raw("CONCAT(mAvatar.path,mAvatar.filename,'.',mAvatar.extension) AS nameAvatar"),
                "mBanner.id as idBanner",
                DB::raw("CONCAT(mBanner.path,mBanner.filename,'.',mBanner.extension) AS nameBanner")
            )
                ->leftJoin("user_info", "user_info.user_id", "=", "users.id")->leftJoin("pro_user_info", "pro_user_info.user_id", "=", "users.id")
                ->leftJoin("media as mAvatar", "mAvatar.id", "=", "user_info.avatar")->leftJoin("media as mBanner", "mBanner.id", "=", "pro_user_info.probannerimg")
                ->where('users.id', '=', $request->id)->first();

            if ($data) {
                $userPhones = [];
                $userEmails = [];
                $userWtsps = [];
                $userContacts = UserContacts::where('user_id', '=', $data->id)->get();
                foreach ($userContacts as $key => $value) {
                    if ($value->type == "phone") {
                        $userPhones[] = $value->value;
                    }
                    if ($value->type == "whatsapp") {
                        $userWtsps[] = $value->value;
                    }
                    if ($value->type == "email") {
                        $userEmails[] = $value->value;
                    }
                }
                $data->userPhones = $userPhones;
                $data->userEmails = $userEmails;
                $data->userWtsps = $userWtsps;
            }

            return $this->showAny($data);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function editProfile(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'id' => 'required|integer',
                'firstname' => 'string|nullable',
                'lastname' => 'string|nullable',
                'username' => 'string|unique:users,username,' . $req->id . '|nullable',
                //'email' => 'string|unique:users,email,' . $req->id . '|nullable',
                'phone' => 'string|phone:AUTO,MA,FR,BE,UK,NL,US' . $req->id . '|nullable',
                'user_image_id' => 'integer|nullable',
                'bio' => 'string|nullable',
                'phones' => 'array|nullable',
                'phones.*.value' => 'string',
                'emails' => 'array|nullable',
                'emails.*.value' => 'email',
                'whatsapp' => 'array|nullable',
                'whatsapp.*.value' => 'string',
                'city' => 'integer|nullable',
                'company' => 'string|nullable',
                'website' => 'string|nullable',
                'company_banner_id' => 'integer|nullable',
            ]);


            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }

            // find the user
            $user = \App\Models\User::find($req->id);

            // if not exist
            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

            DB::beginTransaction();

            // update the user
            $user->firstname = $req->firstname ?? $user->firstname;
            $user->lastname = $req->lastname ?? $user->lastname;
            $user->username = $req->username ?? $user->username;
            $user->email = $req->email ?? $user->email;
            $user->phone = $req->phone ?? $user->phone;

            $user->save();

            $contact_ids = [];

            // get all $req->phones ids
            if ($req->phones) {
                foreach ($req->phones as $phone) {
                    if (isset($phone['id'])) {
                        $contact_ids[] = $phone['id'];
                    }
                }
            }

            // get all $req->emails ids
            if ($req->emails) {
                foreach ($req->emails as $email) {
                    if (isset($email['id'])) {
                        $contact_ids[] = $email['id'];
                    }
                }
            }

            // get all $req->whatsapp ids
            if ($req->whatsapp) {
                foreach ($req->whatsapp as $whatsapp) {
                    if (isset($whatsapp['id'])) {
                        $contact_ids[] = $whatsapp['id'];
                    }
                }
            }

            // delete all contacts who are not in $contact_ids
            UserContact::where('user_id', $req->id)->whereNotIn('id', $contact_ids)->delete();

            if ($req->phones) {
                foreach ($req->phones as $key => $phone) {
                    $user_contact = new UserContact();

                    if (isset($phone['id'])) {
                        $user_contact = UserContact::find($phone['id']);
                    }

                    $user_contact->user_id = $req->id;
                    $user_contact->type = 'phone';
                    $user_contact->value = $phone['value'];
                    $user_contact->save();
                }
            }

            if ($req->emails) {
                foreach ($req->emails as $key => $email) {
                    $user_contact = new \App\Models\UserContact();

                    if (isset($email['id'])) {
                        $user_contact = \App\Models\UserContact::find($email['id']);
                    }

                    $user_contact->user_id = $req->id;
                    $user_contact->type = 'email';
                    $user_contact->value = $email['value'];
                    $user_contact->save();
                }
            }

            if ($req->whatsapp) {
                foreach ($req->whatsapp as $key => $whatsapp) {
                    $user_contact = new \App\Models\UserContact();

                    if (isset($whatsapp['id'])) {
                        $user_contact = \App\Models\UserContact::find($whatsapp['id']);
                    }

                    $user_contact->user_id = $req->id;
                    $user_contact->type = 'whatsapp';
                    $user_contact->value = $whatsapp['value'];
                    $user_contact->save();
                }
            }

            if (in_array($user->usertype, $this->proUsersTypes)) {

                $pro_user_info = \App\Models\pro_user_info::where('user_id', $user->id)->first();

                if (!$pro_user_info) {
                    $pro_user_info = new \App\Models\pro_user_info();
                }

                $pro_user_info->user_id = $user->id;
                $pro_user_info->company = $req->company;
                $pro_user_info->city = $req->city;
                $pro_user_info->website = $req->website;
                $pro_user_info->probannerimg = $req->company_banner_id;

                $check = $pro_user_info->save();

                if (!$check) {
                    DB::rollBack();
                    return $this->errorResponse("Somthing wrong", 400);
                }
            }


            $user_info = \App\Models\user_info::where('user_id', $user->id)->first();

            if (!$user_info) {
                $user_info = new \App\Models\user_info();
            }

            $user_info->user_id = $user->id;
            $user_info->avatar = $req->user_image_id;
            $user_info->bio = $req->bio;

            $check = $user_info->save();


            DB::commit();

            return $this->showAny(null);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }


    public function updatePassword(Request $request){


        $validator = Validator::make($request->all(),[

             "password" => "required|min:8|max:24|confirmed",
             "old_password" => "required|min:8|max:24"
        ],[

             "old_password.required"=> "Le champ mot de passe actuel est obligatoire",

        ]);




        if($validator->fails()){

            return $this->errorResponse($validator->errors(),422);
        }

        $user = User::find(auth()->id());



        if(!Hash::check($request->old_password,$user->password)){

            return $this->errorResponse([
               'errors' => 'Mot de passe actuel est incorrecte',
               'status'=> 409
           ],409);
        }

        else {
           $user->password = Hash::make($request->password);
           $user->save();

           return $this->showAny('Mot de passe modifié avec succés',200);
        }

    }
}
