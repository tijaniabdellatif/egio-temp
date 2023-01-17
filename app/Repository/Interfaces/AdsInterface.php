<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface AdsInterface
{


    /**
     * get all users
     *
     * @return void
     */


    public function getAdsByCategory(Model $ads, $id);

}
