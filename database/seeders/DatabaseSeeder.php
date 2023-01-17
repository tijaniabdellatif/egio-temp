<?php

namespace Database\Seeders;

use App\Models\pro_user_info;
use Database\Seeders\adsSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\CitySeeder;
use Database\Seeders\UserSeeder;
use Database\Factories\adsFactory;
use Database\Seeders\RegionSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\CountrySeeder;
use Database\Seeders\AuthTypeSeeder;
use Database\Seeders\ProvinceSeeder;
use Database\Seeders\UserTypeSeeder;
use Database\Seeders\media_typeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '-1');
        $this->call([

        LocsSeeder::class,
        RoleAndPermissionSeeder::class,
        UserTypeSeeder::class,
        AuthTypeSeeder::class,
        UserSeeder::class,
        CategoriesSeeder::class,
        StaticSeeder::class,
        UserInfoSeeder::class,
        adsSeeder::class,

        ]);
    }
}
