<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends ApiController
{

    public function getSeo(Request $req)
    {

        $seo = $this::_getSeo();

        return $this->showAny($seo);

    }

    public function postSeo(Request $req)
    {

        // validate request check if $req->seo is json data
        $validator = Validator::make($req->all(), [
            'seo' => 'required|json',
        ]);

        // store seo in /storage/db/seo.json

        if ($validator->fails()) {
            // return response()->json([
            //     'status' => 'error',
            //     'message' => $validator->errors()->first(),
            // ]);
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        // create or update seo.json file
        $settings = \App\Models\settings::first();
        if (!$settings) {
            $settings = new \App\Models\settings;
        }

        $settings->seo = $req->seo;
        if(!$settings->isDirty()){
            return $this->showAny('Rien a modifier',409);
        }
        $settings->save();

        // create or update seo.json file in /storage/db/seo.json
        // convert $req->seo to json and save it to /storage/db/seo.json
        $seo = json_decode($req->seo, true);
        $seo = json_encode($seo);

        file_put_contents(storage_path("db". DIRECTORY_SEPARATOR ."seo.json"), $seo);

        return $this->showAny($seo);
    }

    public static function _getSeo(){
        // get seo from db/seo.json

        //check if seo exists
        if (!file_exists(storage_path("db". DIRECTORY_SEPARATOR ."seo.json"))) {

            // get seo from Settings model

            $settings = \App\Models\settings::first();


            if (!$settings) {
                $settings = new \App\Models\settings;
            }
            // save seo to db/seo.json
            $seo = $settings->seo;

            $seo = json_decode($seo, true);
            $seo = json_encode($seo);
            file_put_contents(storage_path("db". DIRECTORY_SEPARATOR ."seo.json"), $seo);
        }

        $seo = file_get_contents(storage_path("db". DIRECTORY_SEPARATOR ."seo.json"));

        return $seo;
    }

    public function getLinks()
    {
        $settings = \App\Models\settings::get(['facebook','instagram','linkedin','youtube']);
        return $this->showAny($settings);
    }

}
