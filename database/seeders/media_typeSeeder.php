<?php

namespace Database\Seeders;

use App\Models\media_type;
use Illuminate\Database\Seeder;

class media_typeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        media_type::factory()->count(10)->create();
    }
}
