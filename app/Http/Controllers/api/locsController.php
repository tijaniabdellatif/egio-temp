<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\regions;

class locsController extends ApiController
{

    // filter
    public function regionFilter(Request $request){

        // build query using $data
        $query = regions::select('regions.*','countries.name as country')->join('countries','countries.id','regions.country_id');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'name' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'country_id' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
            ], // allowed cols to filter by
            true // passing data as json (if true) or php array (if false)
        );

        try{
            $result = [];
            //check if query has sort and order
            if(isset($request->sort) && isset($request->order)){
                $result = $query->orderBy($request->sort, $request->order);
            }

            //check if request has per_page parameter
            if($request->has('per_page')){
                // get the data
                $result = $query->paginate($request->per_page);
            }
            else{
                // get the data
                $result = $query->get();
            }

            // get sql statement
            // $sql = $query->toSql();
            // dd($sql);

            // return success message with data
            return $this->showAny($result);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),500);
        }


    }

    public function SaveRegion(Request $request)
    {
        try{
            $regions = new regions();
            $regions->name = $request->name;
            if(is_array($request->coordinates)){
                foreach ($request->coordinates as $key => $value) {
                    
                }
            }
            $regions->coordinates = $request->coordinates;

            $check = $regions->save();
            if(!$check){
                return $this->errorResponse('Operation faild',409);
            }
            else{
                return $this->showAny(null);
            }
        }
        catch(\Exception $e){
            return $this->errorResponse($e->getMessage(),500);
        }
    }

}
