<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\clicks;

class ItemController extends ApiController
{
    const CALL_IMAGES = 'CALL adsImages(?)';

    public function getItem(Request $req)
    {
        $id = $req->id;

        // check if id is exist
        if (!$id) {
            return $this->errorResponse("Item introuvable", 404);
        }

        // get annonce
        //$annonce = ads::find($id);
        $annonce = DB::select(
            "SELECT a.* , ct.name as 'city' , n.name as 'neighborhood' , u.phone as user_phone , u.email as user_email ,
                    u.username , u.usertype , c.title as 'categorie' , c.parent_cat , c.type , ph1.value as c_phone
                    , ph2.value as c_phone2 , em.value as c_email , wtsp1.value as c_wtsp
                    , CONCAT(m.path,m.filename,'.',m.extension) AS avatar , s.designation as standing_des , pui.company
                    FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                    LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id
                    LEFT JOIN user_info ui on u.id = ui.user_id LEFT JOIN media m on ui.avatar = m.id
                    LEFT JOIN `pro_user_info` pui on u.id = pui.user_id
                    LEFT JOIN `user_contacts` ph1 on a.phone = ph1.id
                    LEFT JOIN `user_contacts` ph2 on a.phone2 = ph2.id
                    LEFT JOIN `user_contacts` wtsp1 on a.wtsp = wtsp1.id
                    LEFT JOIN `user_contacts` em on a.email = em.id
                    LEFT JOIN `standing` s on a.standing = s.id
                    WHERE a.id = $id
        "
        );

        // if not found
        if (!isset($annonce[0])) {
            return $this->errorResponse("Cette annonce n'existe pas", 404);
        }

        $result = $annonce[0];
        $result->images = DB::select(self::CALL_IMAGES, array($result->id));
        $result->videos = DB::select('CALL adsVideos(?)', array($result->id));
        $result->audios = DB::select('CALL adsAudios(?)', array($result->id));
        $result->nearbyPlaces = DB::select('SELECT n.title , n.distance , p.designation , p.icon from nearby_places n INNER JOIN places_type p on n.id_place_type = p.id where n.id_ad = ? order by n.distance', array($result->id));

        return $this->showAny($result);
    }

    public function addClick(Request $req)
    {
        $id = $req->id;
        $type = $req->type; // hit , wtsp , phone

        // check if id is exist
        if (!$id || !$type) {
            return $this->errorResponse("Requete introuvable", 400);
        }

        $click = new clicks();
        $click->type = $type;
        $click->ad_id = $id;

        $check = $click->save();

        if (!$check) {
            return $this->errorResponse("Contactez votre administrateur", 409);
        } else {
            return $this->showAny(null);
        }
    }

    public function similarItems(Request $req)
    {
        $city = $req->city;
        $cat_id = $req->cat_id;
        $price = $req->price;
        $type = $req->type;
        $id = $req->id;

        // check if id is exist
        if (!$city || !$cat_id || !$price || !$type || !$id) {
            return $this->errorResponse("bad request", 400);
        }

        $priceFilter = '';
        if ($type == "vente") {
            $priceMin = ($price * 1) - 100000;
            $priceMax = ($price * 1) + 100000;
            $priceFilter = " AND (a.price BETWEEN $priceMin AND $priceMax) ";
        }
        if ($type == "location") {
            $priceMin = ($price * 1) - 1000;
            $priceMax = ($price * 1) + 1000;
            $priceFilter = " AND (a.price BETWEEN $priceMin AND $priceMax) ";
        }
        if ($type == "vacance") {
            $priceMin = ($price * 1) - 300;
            $priceMax = ($price * 1) + 300;
            $priceFilter = " AND (a.price BETWEEN $priceMin AND $priceMax) ";
        }

        $data = DB::select(
            "SELECT a.id , a.title , a.price , a.description , a.price_curr , a.ref , a.surface ,a.`rooms` ,
                    a.bedrooms , a.bathrooms , a.loccity , a.locdept , a.locdept2 , ct.name as 'city' ,n.name as 'neighborhood' ,
                    a.id_user , u.username , u.usertype , a.catid , c.title as 'categorie' , c.parent_cat , a.published_at as 'date' ,
                    CONCAT(m.path,m.filename,'.',m.extension) AS avatar
                    FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                    LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id
                    LEFT JOIN user_info ui on u.id = ui.user_id LEFT JOIN media m on ui.avatar = m.id
                    WHERE a.status = '10' and a.expiredate > CURRENT_TIMESTAMP
                    AND a.id <> $id AND a.loccity = $city AND a.catid = $cat_id $priceFilter
                    order by a.published_at DESC limit 3
        "
        );

        $result = [];
        foreach ($data as $value) {
            $obj = $value;
            $obj->images = DB::select('CALL adsImages(?)', array($value->id));
            $result[] = $obj;
        }

        return $this->showAny($result);
    }

    public function disposItems(Request $req)
    {
        $id = $req->id;

        // check if id is exist
        if (!$id) {
            return $this->errorResponse("bad request", 400);
        }

        $data = DB::select(
            "SELECT a.id , a.title , a.price , a.description , a.price_curr , a.ref , a.surface ,a.`rooms` ,
                    a.bedrooms , a.bathrooms , a.loccity , a.locdept , a.locdept2 , ct.name as 'city' ,n.name as 'neighborhood' ,
                    a.id_user , u.username , u.usertype , a.catid , c.title as 'categorie' , c.parent_cat , a.published_at as 'date' ,
                    CONCAT(m.path,m.filename,'.',m.extension) AS avatar
                    FROM `ads` a INNER JOIN `users` u on a.id_user = u.id INNER JOIN `cats` c on a.catid = c.id
                    LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id
                    LEFT JOIN user_info ui on u.id = ui.user_id LEFT JOIN media m on ui.avatar = m.id
                    WHERE a.status = '10' and a.expiredate > CURRENT_TIMESTAMP
                    AND a.parent_project = $id
                    order by a.published_at DESC
        "
        );

     

        $result = [];
        foreach ($data as $value) {
            $obj = $value;
            $obj->images = DB::select('CALL adsImages(?)', array($value->id));
            $result[] = $obj;
        }

        return $this->showAny($result);
    }

    public function tags(Request $req)
    {
        $id = $req->id;

        // check if id is exist
        if (!$id) {
            return $this->errorResponse("Item introuvable", 404);
        }


        $annonce = DB::select(
            "SELECT a.title , ct.name as 'city' , n.name as 'neighborhood',
                     c.title as 'categorie' , c.parent_cat , c.type
                     , s.designation as standing_des
                    FROM `ads` a INNER JOIN `cats` c on a.catid = c.id
                    LEFT JOIN cities ct on a.loccity = ct.id LEFT JOIN neighborhoods n on a.locdept = n.id
                    LEFT JOIN `standing` s on a.standing = s.id
                    WHERE a.id = $id
        "
        );

        $titleexplode = explode(" ", $annonce[0]->title);
        $result = $annonce[0];

        return $this->showAny($result);
    }
}
