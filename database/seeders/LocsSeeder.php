<?php

namespace Database\Seeders;

use App\Models\cities;
use App\Models\countries;
use App\Models\neighborhoods;
use App\Models\provinces;
use App\Models\Region;
use Grimzy\LaravelMysqlSpatial\Types\Geometry;
use Illuminate\Database\Seeder;

class LocsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     countries::create(["id"=>1,"name"=>"Maroc","code"=>"MA","coordinates"=>null]);


        $regions = json_decode(file_get_contents(storage_path() . "/finalLocsData/regions.json"), true);

        foreach ($regions as $region) {
            Region::create([
                "id"=>$region['id'],
                "name"=>$region['name'],
                "country_id"=>1,
                "coordinates"=>null
            ]);
        }

        $provinces = json_decode(file_get_contents(storage_path() . "/finalLocsData/provinces.json"), true);

        foreach ($provinces as $province) {
            provinces::create([
                "id"=>$province['id'],
                "name"=>$province['name'],
                "region_id"=>$province['region_id'],
                "coordinates"=>null
            ]);
        }

        $cities = json_decode(file_get_contents(storage_path() . "/finalLocsData/cities.json"), true);

        foreach ($cities as $city) {
            cities::create([
                "id"=>$city['id'],
                "name"=>$city['name'],
                "province_id"=>$city['province_id'],
                "lat"=>$city['lat']??null,
                "lng"=>$city['lng']??null,
                "coordinates"=>null
            ]);
        }

        $neighborhoods = json_decode(file_get_contents(storage_path() . "/finalLocsData/neighborhoods.json"), true);
        /*$neighborhoods = [
            ["id"=>1,"name"=>"Anfa","city_id"=>1,"coordinates"=>Geometry::fromJson("")],
            ["id"=>2,"name"=>"Ain Diab","city_id"=>1,"coordinates"=>null],
            ["id"=>3,"name"=>"Maarif","city_id"=>1,"coordinates"=>null],
        ];*/
        foreach ($neighborhoods as $neighborhood) {
            neighborhoods::create([
                "id"=>$neighborhood['id'],
                "name"=>$neighborhood['name'],
                "city_id"=>$neighborhood['city_id'],
                "lat"=>$neighborhood['lat']??null,
                "lng"=>$neighborhood['lng']??null,
                "coordinates"=>null
            ]);
        }
    }
}

