<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Procedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $getParentCats = "
            DROP PROCEDURE IF EXISTS `getParentCats`;
            CREATE PROCEDURE `getParentCats`()
            BEGIN
                SELECT *FROM cats WHERE cats.parent_cat IS NULL ;
            END;
        ";
        DB::unprepared($getParentCats);

        $getPhones = "
            DROP PROCEDURE IF EXISTS `getPhones`;
            CREATE PROCEDURE `getPhones`(
                IN adId INTEGER, IN dateFrom DATE, IN dateTo DATE
            )
            BEGIN
               SELECT COUNT('*') FROM clicks WHERE ad_id=adId AND `type`= 'phone' BETWEEN dateFrom AND dateTo;
            END;
        ";
        DB::unprepared($getPhones);

        $getTypes = "
            DROP PROCEDURE IF EXISTS `getTypes`;
            CREATE PROCEDURE `getTypes`()
            BEGIN
                SELECT * FROM option_type ;
            END;
        ";
        DB::unprepared($getTypes);

        $adsAudios = "
            DROP PROCEDURE IF EXISTS `adsAudios`;
            CREATE PROCEDURE `adsAudios`(

                IN adId INTEGER

               )
            BEGIN
               SELECT media_id AS id, CONCAT(media.path,media.filename,'.',media.extension) AS name
               FROM ad_media
               JOIN  media  ON media.id = ad_media.media_id
               WHERE ad_media.ad_id = adId AND media_type = 3
               order by ad_media.order;
            END;
        ";
        DB::unprepared($adsAudios);

        $adsVideos = "
            DROP PROCEDURE IF EXISTS `adsVideos`;
            CREATE PROCEDURE `adsVideos`(

                IN adId INTEGER

               )
            BEGIN
               SELECT media_id AS id, CONCAT(media.path,media.filename,'.',media.extension) AS name
               FROM ad_media
               JOIN  media  ON media.id = ad_media.media_id
               WHERE ad_media.ad_id = adId AND media_type = 2
               order by ad_media.order;
            END;
        ";
        DB::unprepared($adsVideos);

        $adsImages = "
            DROP PROCEDURE IF EXISTS `adsImages`;
            CREATE PROCEDURE `adsImages`(

                IN adId INTEGER

               )
            BEGIN
               SELECT media_id AS id, CONCAT(media.path,media.filename,'.',media.extension) AS name
               FROM ad_media
               JOIN  media  ON media.id = ad_media.media_id
               WHERE ad_media.ad_id = adId AND media_type = 1
               order by ad_media.order;
            END;
        ";
        DB::unprepared($adsImages);

        $bgImage = "
            DROP PROCEDURE IF EXISTS `bgImage`;
            CREATE PROCEDURE `bgImage`(

                IN adId INTEGER

               )
            BEGIN
               SELECT bg_image AS id, CONCAT(media.path,media.filename,'.',media.extension) AS name
               FROM ads
               JOIN  media  ON media.id = ads.bg_image
               WHERE ads.id = adId;
            END;
        ";
        DB::unprepared($bgImage);

        $getUsersAllInfo = "
            DROP PROCEDURE IF EXISTS `getUsersAllInfo`;
            CREATE PROCEDURE `getUsersAllInfo`(

                IN userId INTEGER

               )
            BEGIN
               SELECT users.firstname , users.lastname, users.username, users.assigned_ced , users.email, users.phone, users.usertype, users.birthdate, users.created_at, users.updated_at, users.expiredate, users.authtype, users.assigned_user, user_info.bio, user_info.avatar, user_info.gender, user_info.avatar, user_info.likes, pro_user_info.address, pro_user_info.city, pro_user_info.video, pro_user_info.audio, pro_user_info.image, pro_user_info.company, pro_user_info.website, pro_user_info.probannerimg
               FROM users
               LEFT JOIN user_info ON user_info.user_id = users.id
               LEFT JOIN pro_user_info ON pro_user_info.user_id = users.id
               WHERE users.id = userId
               LIMIT 1;
            END;
        ";
        DB::unprepared($getUsersAllInfo);

        $nearbyPlaces = "
            DROP PROCEDURE IF EXISTS `nearbyPlaces`;
            CREATE PROCEDURE `nearbyPlaces`(

                IN adId INTEGER

               )
            BEGIN
               SELECT nearby_places.*,places_type.designation AS place_type, places_type.icon AS place_type_icon
               FROM nearby_places JOIN places_type
               WHERE nearby_places.id_place_type = places_type.id AND nearby_places.id_ad = adId;
            END;
        ";
        DB::unprepared($nearbyPlaces);

        $optionsObj = "
            DROP PROCEDURE IF EXISTS `optionsObj`;
            CREATE PROCEDURE `optionsObj`(

                IN adId INTEGER

               )
            BEGIN
               SELECT o.option_id
               FROM `options` o inner join `options_catalogue` oc on o.option_id = oc.id
               where o.ad_id = adId
               and o.status = '10'
               and TIMESTAMPADD(DAY,oc.duration ,o.timestamp) > CURRENT_TIMESTAMP
               order by o.id desc;
            END;
        ";
        DB::unprepared($optionsObj);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
