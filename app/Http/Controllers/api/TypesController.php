<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \App\Models\types;

class TypesController extends ApiController
{

    // filter
    public function filter(Request $request){

        // build query using $data
        $query = types::select('*');

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
                'designation' => [
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

    // create a new plan
    public function create(Request $request){

        try{
            // validate request
            $validator = Validator::make($request->all(), [
                'designation' => 'required|string|max:100'
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // create a new plan
            $type = new types();

            // set plan data
            $type->designation = $request->designation;

            // save plan
            $type->save();

            return $this->showAny($type);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }

    // update a plan
    public function update(Request $request){

        try{
            // validate request
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'designation' => 'required|string|max:100',
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // get cat
            $type = types::find($request->id);

            // check if plan exists
            if(!$type){
                return $this->errorResponse('Type not found',404);
            }

            // set plan data
            $type->designation = $request->designation;

            // save plan
            $type->save();

            return $this->showAny($type);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }

}
