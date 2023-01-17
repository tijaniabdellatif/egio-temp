<?php

namespace App\Http\Controllers\MobileAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\MobileAPI\MobileApiController;
use App\Models\ads;
use App\Models\cats;
use App\Repository\Interfaces\AdsInterface;

class AdsController extends MobileApiController
{

    private $handler;

    public function __construct(AdsInterface $handler)
    {

        $this->handler = $handler;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAdsByCategory(ads $ads, Request $request)
    {

        $rules = [

            'id' => 'required|integer'
        ];
        $this->validate($request, $rules);
        $data = $this->handler->getAdsByCategory($ads, $request->id);
        return $this->showAll($data, 200, ['message' => 'Success', 'flag' => 'success']);
    }


}
