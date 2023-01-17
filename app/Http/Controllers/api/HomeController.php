<?php

namespace App\Http\Controllers\api;

use App\Models\ads;
use App\Models\cats;
use App\Models\media;
use App\Models\Region;
use App\Models\ad_media;
use App\Models\provinces;
use App\Models\standings;
use Illuminate\Http\Request;
use App\Models\property_type;
use Illuminate\Support\Facades\DB;

class HomeController extends ApiController
{
    public function getPropertiesType()
    {
        $allPropetiesType = property_type::all();
        return $this->showAny($allPropetiesType);
    }

    public function getAllStanding()
    {
        $allStandings = standings::all();
        return $this->showAny($allStandings);
    }

    public function getAllAds()
    {
        $allAds = ads::where("is_project", true);
        return $this->showAny($allAds);
    }

    public function getExpiredAds()
    {
        $expiredAds = ads::where("expireDate", ">", date(now()));
        return $this->showAny($expiredAds);
    }

    public function getCategories()
    {
        $categories = cats::all()->where('type', '!=', null);
        return $this->showAny($categories);
    }

    public function getCategoriesType()
    {
        $categoriesType = cats::all()->where('type', '!=', null)->pluck('type');
        return $this->showAny($categoriesType);
    }

    public function getCategoriesByType(string $type)
    {
        $getCategoriesByType = cats::all()->where('type', '=', $type);
        return $this->showAny($getCategoriesByType);
    }

    public function categories(Request $request)
    {
        $categories = cats::whereNotNull('parent_cat');
        // dd($request->id);
        if (isset($request->id) && $request->id) {
            $categories = $categories->where('parent_cat', '=', $request->id);
        }
        return $this->showAny($categories->get());
    }

    public function getMediaByOrder()
    {
        $adMedia = new ad_media();
        $media = new media();
        return $adMedia->pluck('order')->where('media_id', '=', $media->mediatype);
    }

    public function getStoriesAds(Request $request)
    {

        // validate univer & seens using validator
        $validator = validator()->make($request->all(), [
            'univer' => 'integer',
            'seens' => 'array',
            'seens.*' => 'integer'
        ]);

        // check if not valid
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        try {
            $filter = '';

            if ($request->univer) {
                $filter .= " AND c.parent_cat = '$request->univer' ";
            }

            if ($request->seens) {
                $filter .= " AND a.id NOT IN (" . implode(',', $request->seens) . ") ";
            }

            if (!$request->count) {
                $request->count = 7;
            }

            $data= DB::select(
                        "SELECT a.id , a.title , a.price , a.price_curr , ct.name as 'city' , n.name as 'neighborhood' ,
                                a.id_user , u.username , u.usertype , a.catid , c.title as 'categorie' , c.parent_cat , a.published_at as 'date' ,
                                CONCAT(m.path,m.filename,'.',m.extension) AS avatar
                        FROM `ads` a
                        INNER JOIN `users` u on a.id_user = u.id
                        INNER JOIN `cats` c on a.catid = c.id
                        LEFT JOIN cities ct on a.loccity = ct.id
                        LEFT JOIN neighborhoods n on a.locdept = n.id
                        INNER JOIN options o ON o.ad_id = a.id
                        INNER JOIN options_catalogue op on op.id = o.option_id
                        LEFT JOIN user_info ui on u.id = ui.user_id
                        LEFT JOIN media m on ui.avatar = m.id
                        WHERE o.status = '10' and TIMESTAMPADD(DAY,op.duration ,o.timestamp) > CURRENT_TIMESTAMP
                        and op.type_id = 1 and a.status = '10' and a.expiredate > CURRENT_TIMESTAMP
                        $filter
                        order by RAND() limit $request->count;"
            );

            $result = [];

            foreach ($data as $value) {
                $obj = $value;
                $obj->images = DB::select('CALL adsImages(?)', array($value->id));
                //$obj->videos = DB::select('CALL adsVideos(?)',array($value->id));
                //$obj->audios = DB::select('CALL adsAudios(?)',array($value->id));
                $obj->premium = true;
                $result [] = $obj;
            }

            return $this->showAny($result);

        }
        catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }

    }

    public function getProjectsAds(Request $request)
    {
        try {
            $filter = '';

            if ($request->univer) {
                $filter .= " AND c.parent_cat = '$request->univer' ";
            }

            if (!$request->count) {
                $request->count = 7;
            }

            $data= DB::select(
                "SELECT
                    a.id , a.title , a.price , a.description , a.price_curr , a.ref ,
                    a.loccity , a.locdept , a.locdept2 , ct.name as 'city' , n.name as 'neighborhood' ,
                    a.id_user , u.username , u.usertype , a.catid , c.slug , c.title as 'categorie' , c.parent_cat , a.published_at as 'date' ,
                    CONCAT(m.path,m.filename,'.',m.extension) AS 'avatar'
                    FROM `ads` a
                    INNER JOIN `users` u on a.id_user = u.id
                    INNER JOIN `cats` c on a.catid = c.id
                    LEFT JOIN cities ct on a.loccity = ct.id
                    LEFT JOIN neighborhoods n on a.locdept = n.id
                    LEFT JOIN user_info ui on u.id = ui.user_id
                    LEFT JOIN media m on ui.avatar = m.id
                    WHERE a.status = '10' and a.expiredate > CURRENT_TIMESTAMP
                    AND a.is_project = 1
                    $filter
                    order by RAND() limit $request->count
            "
            );

            $result = [];

            foreach ($data as $value) {
                $obj = $value;
                $obj->images = DB::select('CALL adsImages(?)', array($value->id));
                //$obj->videos = DB::select('CALL adsVideos(?)',array($value->id));
                //$obj->audios = DB::select('CALL adsAudios(?)',array($value->id));
                $obj->premium = true;
                $result [] = $obj;
            }

            return $this->showAny($result);

        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function getRegions(){
          $provinces = provinces::with(["cities","region"])->get();
          return $this->showAny($provinces,200);
    }


}
