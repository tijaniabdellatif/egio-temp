<?php

namespace Database\Seeders;

use App\Models\options_catalogue;
use App\Models\option_type;
use App\Models\options;
use Illuminate\Database\Seeder;

class OptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        option_type::create([
            
            "designation"=>"A la une 7 jours",
            
        ]);

        option_type::create([
            
            "designation"=>"Premuim",
            
        ]);

        option_type::create([
            
            "designation"=>"Tete de liste",
            
        ]);

        options_catalogue::create([
            
            "designation"=>"A la une",
            "price"=>"100000",
            "type_id"=>"1",
            "duration"=>"7",
            
        ]);

        options_catalogue::create([
            
            "designation"=>"Premuim",
            "price"=>"20000",
            "type_id"=>"2",
            "duration"=>"15",
            
        ]);

        options_catalogue::create([
            
            "designation"=>"Tete de liste",
            "price"=>"5000",
            "type_id"=>"3",
            "duration"=>"30",
            
        ]);

        options::create([
            
            "option_id"=>"1",
            "ad_id"=>"1",
        ]);

        options::create([
            
            "option_id"=>"2",
            "ad_id"=>"2",
        ]);

        options::create([
            
            "option_id"=>"3",
            "ad_id"=>"1",
        ]);

    }
}
