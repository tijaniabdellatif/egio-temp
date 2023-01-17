<?php

namespace Database\Seeders;

use App\Models\auth_type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // auth_type::factory()->count(30)->create();

        // add auth types to auth_types table
        DB::table('auth_type')->insert([
            'id' => 1,
            'designation' => 'normal',
        ]);
        DB::table('auth_type')->insert([
            'id' => 2,
            'designation' => 'google'
        ]);
        DB::table('auth_type')->insert([
            'id' => 3,
            'designation' => 'facebook'
        ]);
    }
}
