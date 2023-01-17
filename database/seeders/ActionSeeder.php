<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     protected $action = "action_name";
    public function run()
    {
        DB::table('actions')->insert([

            $this->action => "read"

        ]);
        DB::table('actions')->insert([

            $this->action => "add"

        ]);
        DB::table('actions')->insert([

            $this->action => "delete"

        ]);
        DB::table('actions')->insert([

            $this->action => "edit"

        ]);
        DB::table('actions')->insert([

            $this->action => "boost"

        ]);
        DB::table('actions')->insert([

            $this->action => "moderate"

        ]);
    }
}
