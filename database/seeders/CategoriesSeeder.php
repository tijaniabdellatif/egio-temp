<?php

namespace Database\Seeders;

use App\Lib\DataManager;
use App\Models\cats;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{

    use DataManager;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $categories = [
            ["id"=>1,"title"=>"Homelist","parent_cat"=>null,"type"=>"","status"=>"10"],
            ["id"=>2,"title"=>"Officelist","parent_cat"=>null,"type"=>"","status"=>"10"],
            ["id"=>3,"title"=>"primelist","parent_cat"=>null,"type"=>"","status"=>"10"],
            ["id"=>4,"title"=>"landlist","parent_cat"=>null,"type"=>"","status"=>"10"],
            ["id"=>5,"title"=>"booklist","parent_cat"=>null,"type"=>"","status"=>"10"],

        ];

        foreach ($categories as $category) {
            cats::create($category);
        }

        $this->catSeed();







    }
}
