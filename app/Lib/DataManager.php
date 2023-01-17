<?php


namespace App\Lib;

use App\Models\ad_media;
use App\Models\ads;
use App\Models\cats;
use App\Models\cities;
use App\Models\Contract;
use App\Models\media;
use App\Models\neighborhoods;
use App\Models\Plan;
use App\Models\pro_user_info;
use App\Models\User;
use App\Models\user_info;
use App\Models\UserContact;
use Illuminate\Support\Facades\Mail;
use App\Mail\SimpleMail;

trait DataManager {


       public function getJson($name){
        return json_decode(file_get_contents(storage_path() ."/finalLocsData/".$name), true);

       }


       public function transformData($name){

        $data = $this->getJson($name);
        $raw =  array();
         for($i=0;$i<count($data);$i++){
             $object = ((object)$data[$i]);
              array_push($raw,
             [

               'Source' => $object->adset_name,
               'Phone' =>  str_contains($object->phone_number,'p:') ? substr($object->phone_number,2) : $object->phone_number,
               'Email' => $object->email,
               'Name' =>  $object->full_name

             ]
           );
         }

           return $raw;

       }

        public function contractsSeeder(){
            try {
                ini_set("max_execution_time", "10000");
                $contracts = $this->getJson('contracts.json');

                if ($contracts) {
                    $success = 0;
                    $failed = 0;

                    foreach ($contracts as $row) {
                        $row = (object)$row;
                        $plan = Plan::find($row->plan);
                        $user = User::find($row->userid);
                        if($plan&&$user){
                            $data = [
                                "user_id"=>$row->userid,
                                "assigned_user"=>86,
                                "plan_id"=>$row->plan,
                                "price"=>$plan->price,
                                "ltc_nbr"=>$plan->ltc_nbr,
                                "ads_nbr"=>$plan->ads_nbr,
                                "date"=>$row->startdate,
                                "duration"=>$plan->duration,
                                "active"=>1
                            ];
                            $check = Contract::create($data);
                            if($check){
                                $success++;
                            }
                            else $failed++;
                        }
                        else $failed++;
                    }
                }
            }
            catch(\Throwable $th) {
                return $th->getMessage();
            }
        }

       public function usersSeeder(){
            try {
                ini_set("max_execution_time", "10000");
                $users = $this->getJson('users.json');

                if ($users) {
                    $users = $users['data'];

                    $success = 0;
                    $failed = 0;

                    foreach ($users as $row) {
                        $row = (object)$row;
                        if (!User::find($row->id)) {
                            $dataUser = [
                                "id" => $row->id,
                                "firstname" => $row->firstname,
                                "lastname" => $row->lastname,
                                "username" => $row->username,
                                "email" => $row->email,
                                "phone" => $row->phone,
                                "coins" => $row->balance,
                                "usertype" => $row->protype=="pro" ? 4 : 5,
                                "authtype" => 1
                            ];

                            $avatar = null;

                            $img = explode('|', $row->avatar)[0];
                            if ($img) {
                                $media = media::create([
                                    "path" => '/images/',
                                    "filename" => explode('.', $img)[0],
                                    "extension" => explode('.', $img)[1],
                                    "media_type" => 1,
                                ]);

                                if ($media) {
                                    $avatar = $media->id;
                                }
                            }



                            $dataUserInfo = [
                                "id" => $row->id,
                                "user_id" => $row->id,
                                "bio" => $row->bio,
                                "avatar" => $avatar,
                            ];


                            $check = User::create($dataUser);
                            if ($check) {
                                user_info::create($dataUserInfo);
                                if ($row->phone2) {
                                    UserContact::create([
                                        "user_id" => $row->id,
                                        "value" => $row->phone2,
                                        "type" => "phone",
                                    ]);
                                }
                                if ($row->phonewtsp) {
                                    UserContact::create([
                                        "user_id" => $row->id,
                                        "value" => $row->phonewtsp,
                                        "type" => "whatsapp",
                                    ]);
                                }
                                if ($row->phonewtsp2) {
                                    UserContact::create([
                                        "user_id" => $row->id,
                                        "value" => $row->phonewtsp2,
                                        "type" => "whatsapp",
                                    ]);
                                }
                                if ($row->phonewtsp3) {
                                    UserContact::create([
                                        "user_id" => $row->id,
                                        "value" => $row->phonewtsp3,
                                        "type" => "whatsapp",
                                    ]);
                                }
                                if ($row->protype=="pro") {
                                    $dataProUserInfo = [
                                        "id" => $row->id,
                                        "user_id" => $row->id,
                                        "company" => $row->company_name,
                                        "website" => $row->website,
                                        "website" => $row->website,
                                    ];
                                    pro_user_info::create($dataProUserInfo);
                                }
                                $success++;
                            } else {
                                $failed++;
                            }
                        } else {
                            $success++;
                        }
                    }
                    return ["success"=>$success,"failed"=>$failed];
                }
                return null;
            }
            catch(\Throwable $th) {
                return $th->getMessage();
            }
       }

       public function sendMailsToAgencies(){
            try{
                ini_set("max_execution_time","10000");
                $users = $this->getJson('newlistcoins.json');
                if ($users) {

                    $success = 0;
                    $failed = 0;

                    foreach ($users as $row) {
                        $row = (object)$row;
                        // Get the user mail and username
                        $host_name = $_SERVER['HTTP_HOST'];

                        Mail::send(new SimpleMail(
                            [
                                "to" =>  iconv('ISO-8859-1','UTF-8//IGNORE', filter_var($row->email, FILTER_SANITIZE_EMAIL)),
                                "subject" => "Listez vos projets sans frais et sans limite !",
                                "view" => "emails.Promo2.fr",
                                "data" => [
                                    "host_name" => $host_name
                                ]
                            ]
                        ));

                    }
                }
            }
            catch(\Throwable $th) {
                return $th->getMessage();
            }
       }

        public function getCoins(){
            try{
                ini_set("max_execution_time","10000");
                $users = $this->getJson('newlistcoins.json');
                if ($users) {

                    $success = 0;
                    $failed = 0;

                    foreach ($users as $row) {
                        $row = (object)$row;
                        $sold = str_replace(",","",$row->new_sold);
                        $user = User::find($row->id)->update(["coins"=>$sold]);
                        if ($user) {
                            /*$coinsManager = new  \App\Lib\CoinsManager();

                            $coinsManager->transaction(
                                $user->id,
                                (int)($row->coins_to_add),
                                'Multilist vous offert un stock supplémentaire de Listcoins',
                                'Multilist vous offert un stock supplémentaire de Listcoins',
                                function ($transaction) use ($user, $success) {
                                    $success++;
                                    return true;
                                }
                            );*/
                            $success++;
                        }
                        else $failed++;
                    }
                    return ["success"=>$success,"failed"=>$failed];
                }
                return null;
            }
            catch(\Throwable $th) {
                return $th->getMessage();
            }
        }

        public function getRefs(){
            try{
                ini_set("max_execution_time","10000");
                $ads = $this->getJson('refs.json');
                if($ads){
                    $ads = $ads['data'];

                    $success = 0;
                    $failed = 0;
                    foreach($ads as $row){
                        $row = (object)$row;

                        if (ads::find($row->id)) {

                            ads::find($row->id)->update([
                                "rssId"=>$row->xml_ref_ad,
                                "ref"=>$row->originref,
                                "created_at"=>$row->createdate,
                            ]);
                            $success++;

                            $has_video = ad_media::join('media','media.id','=','ad_media.media_id')->where('media.media_type','=',2)
                            ->where('ad_id','=',$row->id)->first();

                            if(trim($row->videourl)&&!$has_video){
                                $videos = [];
                                foreach (explode(';', $row->videourl) as $vid) {
                                    if (isset(explode('|', $vid)[0])) {
                                        $videos [] = explode('|', $vid)[0];
                                        break;
                                    }
                                }
                                foreach ($videos as $vid) {
                                    if ((!strpos($vid,"http"))&&isset(explode('.', $vid)[1])&&isset(explode('.', $vid)[0])) {
                                        $media = media::create([
                                            "path" => '/old/',
                                            "filename" => explode('.', $vid)[0],
                                            "extension" => explode('.', $vid)[1],
                                            "media_type" => 2,
                                        ]);

                                        if ($media) {
                                            ad_media::create([
                                                "ad_id" => $row->id,
                                                "media_id" => $media->id,
                                                "order" => 0,
                                                "informations" => '',
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                        else $success++;
                    }
                    return ["success"=>$success,"failed"=>$failed];
                }
                return null;
            }
            catch(\Throwable $th) {
                return $th->getMessage();
            }
        }

        public function adsSeeder(){

            try{
            ini_set("max_execution_time","10000");
                $failed_arr = [];
            //$ads = $this->getJson('ads.json');
            $ads = $this->getJson('ads-city-corrected.json');
            /*TEMPORARY*/
            /*$faildAds = $this->getJson('faildAds.json');
            $faildAds = $faildAds['failed_arr'];
            $finalFaildAds = [];
            foreach($faildAds as $row){
                $finalFaildAds [] = $row['id'];
            }*/
            /*END TEMPORARY*/
            if($ads){
                $ads = $ads['data'];

                $success = 0;
                $failed = 0;
                foreach($ads as $row){

                    $row = (object)$row;
                    $surface = null;
                    $surface2 = null;
                    $chambres = null;
                    $wc = null;
                    $is_project = null;
                    $projet_name = null;
                    $parent_ad = null;
                    $meuble = null;
                    $piece = null;
                    $prio = null;
                    $vr_link = null;

                    if (!ads::find($row->id)/*TEMPORARY*//*&& in_array($row->id,$finalFaildAds)*//*END TEMPORARY*/) {
                        foreach (explode("|", $row->vfields) as $field) {
                            if (isset(explode("=", $field)[0])&&explode("=", $field)[0]==1&&isset(explode("=", $field)[1])) {
                                $chambres = explode("=", $field)[1];
                            }
                            if (isset(explode("=", $field)[0])&&explode("=", $field)[0]==2&&isset(explode("=", $field)[1])) {
                                $surface = explode("=", $field)[1];
                            }
                            if (isset(explode("=", $field)[0])&&explode("=", $field)[0]==10&&isset(explode("=", $field)[1])) {
                                $surface2 = explode("=", $field)[1];
                            }
                            if (isset(explode("=", $field)[0])&&explode("=", $field)[0]==3&&isset(explode("=", $field)[1])) {
                                $wc = explode("=", $field)[1];
                            }
                            if (isset(explode("=", $field)[0])&&explode("=", $field)[0]==4&&isset(explode("=", $field)[1])) {
                                $is_project = explode("=", $field)[1];
                            }
                            if (isset(explode("=", $field)[0])&&explode("=", $field)[0]==6&&isset(explode("=", $field)[1])) {
                                $projet_name = explode("=", $field)[1];
                            }
                            if (isset(explode("=", $field)[0])&&explode("=", $field)[0]==11&&isset(explode("=", $field)[1])) {
                                $meuble = explode("=", $field)[1];
                            }
                            if (isset(explode("=", $field)[0])&&explode("=", $field)[0]==12&&isset(explode("=", $field)[1])) {
                                $piece = explode("=", $field)[1];
                            }
                            if (isset(explode("=", $field)[0])&&explode("=", $field)[0]==13&&isset(explode("=", $field)[1])) {
                                $prio = explode("=", $field)[1];
                            }
                            if (isset(explode("=", $field)[0])&&explode("=", $field)[0]==9&&isset(explode("=", $field)[1])) {
                                $vr_link = explode("=", $field)[1];
                            }
                        }

                        if ($is_project!="true"&&$projet_name) {
                            foreach ($ads as $row1) {
                                $is_projectP = null;
                                $projet_nameP = null;
                                foreach (explode("|", $row1['vfields']) as $field) {
                                    if (isset(explode("=", $field)[0])&&explode("=", $field)[0]==4&&isset(explode("=", $field)[1])) {
                                        $is_projectP = explode("=", $field)[1];
                                    }
                                    if (isset(explode("=", $field)[0])&&explode("=", $field)[0]==6&&isset(explode("=", $field)[1])) {
                                        $projet_nameP = explode("=", $field)[1];
                                    }
                                }
                                if ($is_projectP=="true"&&$projet_name==$projet_nameP) {
                                    $parent_ad = $row1['id'];
                                }
                            }
                        }
                        $valid = true;

                        if($parent_ad){
                            if(!ads::find($parent_ad)) $valid = false;
                        }

                        $city = cities::where('name', 'like', $row->loccity)->first();
                        $user = User::find($row->userid);
                        $cat = cats::find($row->catid);
                        if ($city&&$user&&$cat&&$valid) {
                            $neighborhood = neighborhoods::where('name', 'like', $row->locdept)->first();
                            $data = [
                                "id" => $row->id,
                                "title" => $row->title,
                                "description" => $row->description,
                                "catid" => $row->catid,
                                "price" => $row->price,
                                "price_curr" => $row->pricecur,
                                "loclng" => $row->loclng,
                                "loclat" => $row->loclat,
                                "id_user" => $row->userid,
                                "loccity" => $city->id,
                                "locdept" => $neighborhood ? $neighborhood->id : null,
                                "locdept2" => $neighborhood ? null : $row->locdept,
                                "surface" => $surface,
                                "surface2" => $surface2,
                                "bedrooms" => $chambres,
                                "bathrooms" => $wc,
                                "is_project" => $is_project=="true" ? true : false,
                                "parent_project" => $parent_ad,
                                "meuble" => $meuble,
                                "rooms" => $piece,
                                "project_priority" => $prio=="START" ? 3 : ($prio=="MID" ? 2 : ($prio=="TOP" ? 1 : null)),
                                "vr_link" => $vr_link,
                                "status" => "10",
                                "phone" => -1,
                                "email" => -1,
                                "expiredate" => $row->expiredate??"2023-05-29 00:00:00",
                            ];
                            $images = [];
                            foreach (explode(';', $row->imgname) as $img) {
                                if (isset(explode('|', $img)[0])) {
                                    $images [] = explode('|', $img)[0];
                                }
                            }
                            $check = ads::create($data);
                            if ($check) {
                                $success++;
                                foreach ($images as $img) {
                                    if ((!strpos($img,"http"))&&isset(explode('.', $img)[1])&&isset(explode('.', $img)[0])) {
                                        $media = media::create([
                                            "path" => '/old/',
                                            "filename" => explode('.', $img)[0],
                                            "extension" => explode('.', $img)[1],
                                            "media_type" => 1,
                                        ]);

                                        if ($media) {
                                            ad_media::create([
                                                "ad_id" => $row->id,
                                                "media_id" => $media->id,
                                                "order" => 0,
                                                "informations" => '',
                                            ]);
                                        }
                                    }
                                }
                            } else {
                                $failed++;
                            }
                        } else {
                            $failed++;
                            $failed_arr[]=(object)[
                                "id"=>$row->id,
                                "city"=>$city?$city->id:null,
                                "cat"=>$cat?$cat->id:null,
                                "user"=>$user?$user->id:null,
                                "old_city"=>$row->loccity,
                                "old_cat"=>$row->catid,
                                "old_user"=>$row->userid,
                            ];
                        }
                    }
                    else {
                        $success++;
                    }
                }
                return ["success"=>$success,"failed"=>$failed,"failed_arr"=>$failed_arr];
            }
            return null;
        }
        catch(\Throwable $th) {
            return $th;
        }
        }


       public function userCanSeed()
       {
           $users = $this->getJson('data.json');
           $main=[];
           foreach($users['data'] as $key => $user){
            array_push($main,$user);
          }

       foreach($main as $data){

        if($data['protype'] === "pro"){
            User::create([
                "id"=>$data['id'],
                "firstname"=>$data['firstname'],
                "lastname"=>$data['lastname'],
                "username"=>$data['username'],
                "email"=>$data['email'],
                "phone"=>$data['phone'],
                "password" => $data['password'],
                "usertype"=>4,
                "authtype"=>1,
                "status"=>"10",
                "coins" => 0
            ]);
        }


        if($data['protype']==="par"){

            User::create([
                "id"=>$data['id'],
                "firstname"=>$data['firstname'],
                "lastname"=>$data['lastname'],
                "username"=>$data['username'],
                "email"=>$data['email'],
                "phone"=>$data['phone'],
                "password" => $data['password'],
                "usertype"=>5,
                "authtype"=>1,
                "status"=>"10",
                "coins" => 0
            ]);

        }

       }


        }

    public function userInfoSeed(){
        $data = $this->getJson('user_info.json');
        $raw = [];

        foreach($data['data'] as $info){

          $splited = explode('|',$info['avatarimg']);
          $info['avatarimg'] = $splited['0'];
          array_push($raw,$info);
        }

        $users = User::all('id');

        foreach($users as $user){

            foreach($raw as $value){
                if($user->id === (int) $value['id']){

                    user_info::create([
                        "user_id" => $value['id'],
                        "bio" => $value['bio'],
                        "gender" => $value['gender'],
                        "likes" => $value['likes'],
                        "avatar" => $value['avatarimg']
                    ]);
                }
            }
        }

    }


    public function catSeed(){

        $data = $this->getJson('cats.json');
        $raw = [];



        foreach($data['data'] as $value){

              array_push($raw,$value);
        }


        foreach($raw as $val){

            cats::create([

                 "id" => $val['id'],
                 "title" => $val['title'],
                 "parent_cat" => $val['parent_cat'],
                 "type" => $val['type'],
                 "is_project" => $val['is_project'],
                 "status" => 10
            ]);
        }


        $apart = $this->getJson("appartvendre.json");
        $villa = $this->getJson('villas.json');
        $maisons = $this->getJson('maisons.json');
        $studios = $this->getJson('studios.json');
        $bureau = $this->getJson('bureaux.json');
        $locaux = $this->getJson("locaux.json");
        $vacances = $this->getJson('vacances.json');
        //$landlist = $this->getJson('landlist.json');
        $riads = $this->getJson('riads.json');

        $cats = cats::all();


        foreach($cats as $val){

               if($val['id'] === 10039 || $val['id'] === 10042 || $val['id'] === 10068 || $val['id'] === 10080){

                   $val->update([

                        'fields' => $apart
                   ]);

               }

               if($val['id'] === 10040 || $val['id'] === 10043 || $val['id'] === 10071 || $val['id'] === 10082){

                $val->update([

                     'fields' => $villa
                ]);

              }

              if($val['id'] === 10050 || $val['id'] === 10052){

                $val->update([

                     'fields' => $maisons
                ]);

              }

              if($val['id'] === 10049 || $val['id'] === 10051 || $val['id'] === 10069){

                $val->update([

                     'fields' => $studios
                ]);

              }

              if($val['id'] === 10058 || $val['id'] === 10063 || $val['id'] === 10085){

                $val->update([

                     'fields' => $bureau
                ]);

              }

              if($val['id'] === 10059
              || $val['id'] === 10060
              || $val['id'] === 10064
              || $val['id'] === 10065
              || $val['id'] ===10061
              || $val['id'] ===10066
              || $val['id'] === 10086
              || $val['id'] === 10087
              || $val['id'] === 10088
              ){

                $val->update([

                     'fields' => $locaux
                ]);

              }

              if($val['id'] === 10070){

                $val->update([

                     'fields' => $vacances
                ]);

              }


              /*if($val['id'] === 10054 || $val['id'] === 10055 || $val['id'] === 10056 || $val['id'] === 10084 || $val['id'] === 10090 || $val['id'] === 10076){

                $val->update([

                     'fields' => $landlist
                ]);

              }*/

              if($val['id'] === 10089){

                $val->update([

                    'fields' => $riads
               ]);
              }



               $val->save();
        }
    }





}
