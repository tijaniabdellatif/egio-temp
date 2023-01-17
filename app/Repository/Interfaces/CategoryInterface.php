<?php

namespace App\Repository\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;



interface CategoryInterface {


    /**
     * get all users
     *
     * @return void
     */
    public function parentCategories();
    public function childCategories();
    public function getKeywords();







}
