<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\settings;

class linksController extends ApiController
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
            if(!$settings) return $this->errorResponse('Operation faild',404);
            $settings->facebook = $request->facebook;
            $settings->instagram = $request->instagram;
            $settings->twitter = $request->twitter;
            $settings->linkedin = $request->linkedin;
            $settings->youtube = $request->youtube;
            $settings->tiktok = $request->tiktok;

            $check = $settings->save();
            if(!$check){
                return $this->errorResponse('Operation faild',409);
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
