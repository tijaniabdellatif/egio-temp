<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class favorisController extends ApiController
{
    
    public function getFavorisAds(Request $request){

        try{
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }
            $in = "";
            foreach ($request->ids as $value) {
                if($in != "") $in .=  ", ";
                $in .= $value;
            }
            $list = DB::select(
                "SELECT a.id , a.title , a.price , a.description , a.price_curr , a.ref , a.surface ,a.`rooms` ,
                        a.bedrooms , a.bathrooms , a.loccity , a.locdept , a.locdept2 , ct.name as 'city' ,n.name as 'neighborhood' ,
                        a.id_user , u.username , u.usertype , a.catid , c.title as 'categorie' , c.parent_cat , a.published_at as 'date' ,
                        CONCAT(m.path,m.filename,'.',m.extension) AS avatar , a.loclat , a.loclng
                        FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                        LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id LEFT JOIN provinces p on ct.province_id = p.id
                        LEFT JOIN user_info ui on u.id = ui.user_id LEFT JOIN media m on ui.avatar = m.id
                        WHERE a.status = '10' and a.expiredate > CURRENT_TIMESTAMP
                        AND a.id in ($in)
                        order by a.published_at DESC
            ");

            $result = [];

            foreach ($list as $value) {
                $obj = $value;
                $obj->images = DB::select('CALL adsImages(?)',array($value->id));
                //$obj->videos = DB::select('CALL adsVideos(?)',array($value->id));
                //$obj->audios = DB::select('CALL adsAudios(?)',array($value->id));
                $obj->premium = false;
                $result [] = $obj;
            }

            return $this->showAny($result);

        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }
    }

}
