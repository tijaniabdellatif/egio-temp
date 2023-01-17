<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Lib\DataManager;
use Illuminate\Http\Request;

class LeadsController extends ApiController
{

    use DataManager;

       public function transformLead(){


                $data = $this->transformData('leads1.json');
                return $this->showAny($data);
       }
}
