<?php

namespace App\Http\Controllers\api;

use \App\Models\types;
use App\Models\options;
use App\Models\option_type;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;

class OptionTypesController extends ApiController
{

    // filter
    public function filter(Request $request){

        // build query using $data
        $query = option_type::select('*');

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
                'description' => [
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




    // update option
    public function updateOptionTypeDescription(Request $request){

        try{
            // validate request
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'description' => 'required|min:1|max:200',
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // get cat
            $option_type = option_type::find($request->id);

            // check if plan exists
            if(!$option_type){
                return $this->errorResponse('Type not found',404);
            }

            // set plan data
            $option_type->description = $request->description;
            if($option_type->isClean()){
                return $this->errorResponse('Rien a modifier',409);
            }
            else {

                $option_type->save();
                (new LogActivity())->addToLog($request->id,$request);
            }
            // save plan


            return $this->showAny($option_type);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }


}
