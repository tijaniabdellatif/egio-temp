<?php
namespace Database\Seeders;use App\Models\user_type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserTypeSeeder extends Seeder {
    public function run () {
        // user_type::factory()->count(9)->create();
        // add types to user_types table
        DB::table('user_type')->insert([
            'id' => 1,
            'designation' => 'admin',
            'only_visible_to' => 'admin',
            'role_id'=>1,
        ]);
      
        DB::table('user_type')->insert([
            'id' => 2,
            'designation' => 'moderator manager',
            'only_visible_to' => 'admin',
            'role_id'=>2,
        ]);

        DB::table('user_type')->insert([
            'id' => 6,
            'designation' => 'moderator',
            'only_visible_to' => 'admin',
            'role_id'=>3,
        ]);
        DB::table('user_type')->insert([
            'id' => 3,
            'designation' => 'promoter',
            'only_visible_to' => 'admin',
            'role_id'=>null,
        ]);
        DB::table('user_type')->insert([
            'id' => 4,
            'designation' => 'agency',
            'only_visible_to' => 'admin',
            'role_id'=>null,
        ]);
        DB::table('user_type')->insert([
            'id' => 5,
            'designation' => 'particular',
            'only_visible_to' => 'admin',
            'role_id'=>null,
        ]);
        DB::table('user_type')->insert([
            'id' => 7,
            'designation' => 'comercial',
            'only_visible_to' => 'admin',
            'role_id'=>4,
        ]);
        DB::table('user_type')->insert([
            'id' => 8,
            'designation' => 'ced manager',
            'only_visible_to' => 'admin',
            'role_id'=>5,
        ]);
        DB::table('user_type')->insert([
            'id' => 9,
            'designation' => 'ced',
            'only_visible_to' => 'admin',
            'role_id'=>6,
        ]);
     }
    }

?>
