<?php

namespace Database\Seeders;

use App\Models\pro_user_info;
use Illuminate\Database\Seeder;

class pro_user_infoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        pro_user_info::factory()->count(10)->create();

    }
}
