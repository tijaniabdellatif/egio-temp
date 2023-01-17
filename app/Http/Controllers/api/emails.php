<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Models\email;
use Illuminate\Http\Request;

class emails extends ApiController
{
    // filter query
    public function filter(Request $request)
    {

        // build query using $data
        $query = email::select('*')
                    ->orderBy('id', 'desc');

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
                'ad_id' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'date' => [
                    'operators' => ['=','>','<', '!=','LIKE', 'NOT LIKE'],
                ],
            ], // allowed cols to filter by
            true // passing data as json (if true) or php array (if false)
        );

        //ddQuery($query);

        try{
            //$query = $query->groupBy('ads.id');
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

            // return success message with data
            return $this->showAny($result);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),500);
        }

    }
}
