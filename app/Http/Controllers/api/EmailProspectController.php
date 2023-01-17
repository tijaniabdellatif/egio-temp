<?php

namespace App\Http\Controllers\api;


use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Lib\DataManager;
use Illuminate\Support\Facades\Mail;
use App\Mail\SimpleMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\EmailProspect;


class EmailProspectController extends ApiController
{


    use DataManager;

    private function getEmailsFromJson(){

          try {

            $raw = [];

            $data = $this->getJson('faildAds.json');


            $raw =  array();
            for($i=0;$i<count($data['data']);$i++){
                $object = ((object)$data['data'][$i]);
                array_push($raw,$object);
            }

          return $raw;

          }catch(\Throwable $th){

            return $this->errorResponse($th->getMessage(), 500);
          }
    }




    public function countDataFailed(){

            $data = $this->getEmailsFromJson();

            $raw = [];

            foreach($data as $value){

                if($value->city === null && $value->user !== null){

                     array_push($raw,[

                      'id' => $value->id,
                      "city_manquant" => $value->old_city,
                      "city" => $value->city,
                      "user" => $value->user


                     ]);
                }

            }


            return $this->showAny(($raw),200);
    }


    public function EmailProcess(){

        $emails = $this->getEmailsFromJson();

        try{

                for($i=0;$i<count($emails);$i++){

                  DB::table('email_prospect')->insert([
                    'email' => $emails[$i]->email,
                    'token' => Str::random(19),
                    'is_accepted' => false
                ]);
                }
              return $this->showAny('success',200);

        }catch(\Throwable $th){

            return $this->errorResponse($th->getMessage(), 500);
          }


    }

    public function sendEmails(){

          $prospect = DB::table('email_prospect')->get();

          $host_name = $_SERVER['HTTP_HOST'];

          foreach($prospect as $pros){

            Mail::send(new SimpleMail(
              [
                  "to" => "s.souak@multilist.ma",
                  "subject" => "Votre annonce sur Multilist.immo en un seul clic !",
                  "view" => "emails.EstimationReady.fr",
                  "data" => [

                      "email" => $pros->email,
                      "host_name" => $host_name,
                      "token"=> $pros->token,
                  ]
              ]
          ));

          }

          return $this->showAny("success",200);
    }


    public function getProspect(){

         try{


            $prospect = DB::table('email_prospect')->get();
            return $this->showAny($prospect,200);

         }catch(\Throwable $th){

          return $this->errorResponse($th->getMessage(), 500);
         }
    }


    function acceptCondition(Request $request){

          if($request->isMethod('patch')){

               $id = $request->id;
               $accepted = $request->accepted;

               DB::table('email_prospect')
               ->where('id',$id)
               ->update([
                   'is_accepted' => $accepted
               ]);

               return $this->showAny('success',201);

          }


    }

     public function getSingleProspect(Request $request){

             $token = $request->token;

             if (!$token) {
              return $this->errorResponse("bad request", 400);
            }

            else {


                $singleProspect = DB::table('email_prospect')->where('token', '=', $token)->get();

                return $this->showAny($singleProspect,200);
            }
     }


}
