<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     protected $table = "users_actions";

    public function run()
    {
        
        DB::table($this->table)->insert([

            'user_id' => 2,
            "action_id"=>1,

        ]);

        DB::table($this->table)->insert([

            'user_id' => 2,
            "action_id"=>2,

        ]);

        
        DB::table($this->table)->insert([

            'user_id' => 2,
            "action_id"=>4,

        ]);

        DB::table($this->table)->insert([

            'user_id' => 4,
            "action_id"=>1,

        ]);
    }
}
