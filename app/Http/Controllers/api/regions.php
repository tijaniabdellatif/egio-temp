<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\countries;
use Illuminate\Http\Request;
use App\Models\Region as regModel;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Grimzy\LaravelMysqlSpatial\Types\MultiPolygon;
use Grimzy\LaravelMysqlSpatial\Types\LineString;
use Illuminate\Support\Facades\DB;

class regions extends Controller
{
    public function create(Request $request){
        $data = $request->data;
        try{
            $output = [];
            DB::beginTransaction();
            foreach ($data as $key => $value) {
                $polygon = $value['coordinates'][0][0];
                $lineString = [];
                foreach ($polygon as $key1 => $value1) {
                    $lineString [] = new Point($value1[0], $value1[1]);
                }
                $multipolygon = new MultiPolygon([new Polygon([new LineString($lineString)])]);
                $regions = new regModel();
                $regions->name = $value['title'];
                $regions->coordinates = $multipolygon;
                $regions->country_id = 1;

                $check = $regions->save();
                if(!$check){
                    DB::rollBack();
                    return ['success'=>false,'error' => 'Somthing wrong!','devError'=>'Error on add'];
                }
            }
            DB::commit();
            return ['success'=>true,'data'=>$data];
        }catch(\Throwable $th){
            return ['success'=>false,'data'=>$data,'er'=>$th->getMessage()];
        }
    }


}
