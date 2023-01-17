<?php

namespace Database\Seeders;

use App\Models\media_type;
use App\Models\option_type;
use App\Models\Project_priority;
use App\Models\settings;
use Illuminate\Database\Seeder;

class StaticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        settings::create([
            "id"=>2,
            "max_ad_img"=>"12",
            "max_ad_video"=>"1",
            "max_ad_audio"=>"1",
            "ads_expire_duration"=>"60",
            "users_expire_duration"=>"365",
            "max_user_ads"=>"10",
            "image_max_size"=>"1000",
            "video_max_size"=>"10000",
            "audio_max_size"=>"3000",
            "facebook"=>"https:\/\/www.facebook.com\/Multilist.group",
            "instagram"=>"https:\/\/www.instagram.com\/multilist.immo\/",
            "twitter"=>null,
            "linkedin"=>"https:\/\/www.linkedin.com\/company\/multilist-immo",
            "youtube"=>"https:\/\/www.youtube.com\/channel\/UCNjQB9JoAbAmQJDXrDIjn4w",
            "tiktok"=>null,
            "seo"=>"[{\"icon\": {\"id\": 1390, \"name\": \"\/images\/img_62b5e15245915.ico\"}, \"logo\": {\"id\": 1389, \"name\": \"\/images\/img_62b5e14aa6583.jpg\"}, \"name\": \"multilist\", \"main_bg\": {\"id\": 1391, \"name\": \"\/images\/img_62b5e155d09c9.png\"}, \"meta_tags\": \"\", \"main_color\": \"#ff0000\", \"third_color\": \"#0055ff\", \"secondary_color\": \"#00ff6e\"}, {\"name\": \"booklist\", \"meta_tags\": \"\", \"main_color\": \"#b52483\", \"third_color\": \"\", \"secondary_color\": \"\"}, {\"name\": \"homelist\", \"meta_tags\": \"\", \"main_color\": \"#f64d4b\", \"third_color\": \"\", \"secondary_color\": \"\"}, {\"name\": \"primelist\", \"meta_tags\": \"\", \"main_color\": \"#f3be2e\", \"third_color\": \"\", \"secondary_color\": \"\"}, {\"name\": \"landlist\", \"meta_tags\": \"\", \"main_color\": \"#54c21b\", \"third_color\": \"\", \"secondary_color\": \"\"}, {\"name\": \"officelist\", \"meta_tags\": \"\", \"main_color\": \"#00537d\", \"third_color\": \"\", \"secondary_color\": \"\"}]"
        ]);

        media_type::create(["id"=>1,"designation"=>"image"]);
        media_type::create(["id"=>2,"designation"=>"video"]);
        media_type::create(["id"=>3,"designation"=>"audio"]);

        option_type::create(["id"=>1,"designation"=>"À la une","description"=>"à la une du siteweb"]);
        option_type::create(["id"=>2,"designation"=>"Premium","description"=>"Premuim dans le site"]);
        option_type::create(["id"=>3,"designation"=>"Tête de liste","description"=>"Tête de la liste de recherche"]);

        Project_priority::create(["designation"=>"Top"]);
        Project_priority::create(["designation"=>"Mid"]);
        Project_priority::create(["designation"=>"Start"]);
    }
}
