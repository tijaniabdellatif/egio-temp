<?php

namespace Database\Seeders;

use App\Models\media;
use Illuminate\Database\Seeder;

class mediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        media::factory()->count(10)->create();

    }
}
