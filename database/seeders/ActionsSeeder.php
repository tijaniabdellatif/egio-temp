<?php

namespace Database\Seeders;

use App\Models\Action;
use Illuminate\Database\Seeder;

class ActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actions = [
            ["id"=>1,"controller"=>"adsController","action"=>"create_ad","description"=>"Créer une annonce"],
            ["id"=>2,"controller"=>"UserController","action"=>"add_user","description"=>"Ajouter un utilisateur"],
        ];

        foreach ($actions as $action) {
            Action::create($action);
        }
    }
}
