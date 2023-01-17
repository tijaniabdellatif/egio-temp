<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\settings;

class lifeCycleController extends ApiController
{

    public function getData(Request $request)
    {
        $data = settings::first();
        return $this->showAny($data);
    }

    public function Save(Request $request)
    {
        try{
            $id = settings::select('id')->first()->id;
            $settings = settings::where('id','=',$id)->first();
            if(!$settings) return ['success' => false , 'msg' => 'Operation faild'];
            $settings->max_user_ads = $request->max_user_ads;
            $settings->max_ad_img = $request->max_ad_img;
            $settings->max_ad_video = $request->max_ad_video;
            $settings->max_ad_audio = $request->max_ad_audio;
            $settings->ads_expire_duration = $request->ads_expire_duration;
            $settings->users_expire_duration = $request->users_expire_duration;
            $settings->image_max_size = $request->image_max_size;
            $settings->video_max_size = $request->video_max_size;
            $settings->audio_max_size = $request->audio_max_size;

            if(!$settings->isDirty()){

                return $this->errorResponse('Vous n\'avez rien modifié',409);
            }

            $check = $settings->save();
            if(!$check){
                return $this->errorResponse('Operation failed',409);
            }
            else{
                return $this->showAny(null);
            }
        }
        catch(\Exception $e){
            return $this->errorResponse($e->getMessage(),500);
        }
    }

}
