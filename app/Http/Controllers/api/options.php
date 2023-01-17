<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\option_type;
use App\Models\options_catalogue;

class options extends ApiController
{
    public function getAllOptions(Request $request){
        $options = option_type::all();
        $data = [];
        foreach ($options as $key => $value) {
            $obj = $value;
            $sub_options = options_catalogue::where('type_id','=',$value->id)->get();
            $obj->options = $sub_options;
            $data [] = (object)$obj;
        }
        return $this->showAny($data);
    }
}
