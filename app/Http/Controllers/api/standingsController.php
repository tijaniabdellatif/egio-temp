<?php

namespace App\Http\Controllers\api;

use \App\Models\ads;
use \App\Models\standings;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;

class standingsController extends ApiController
{
    // filter
    public function filter(Request $request){

        // build query using $data
        $query = standings::select('*');

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
    public function createStanding(Request $request){

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
            $standing = new standings();

            // set plan data
            $standing->designation = $request->designation;

            // save plan
            $standing->save();
            (new LogActivity())->addToLog($standing->id,$request);


            return $this->showAny($standing);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }

    public function updateStanding(Request $request){

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
            $standing = standings::find($request->id);

            // check if plan exists
            if(!$standing){
                return $this->errorResponse('Type not found',404);
            }

            // set plan data
            $standing->designation = $request->designation;
            if(!$standing->isDirty()){
                return $this->errorResponse('Rien a modifier',409);
            }
            // save plan
            $standing->save();
            (new LogActivity())->addToLog($standing->id,$request);


            return $this->showAny($standing);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }

    }

    public function deleteStanding(Request $request, standings $standing) {


        try {
            $standing = standings::find($request->id);

            if(!$standing){
                return $this->errorResponse('Standing not found',404);
            }

            // Check if standings exists in an ad to abort delete
            $check = ads::where('standing','=',$request->id)->exists();
            if($check) {
                return $this->errorResponse('Standing déjà utilisé !',401);
            }


            $standing->delete();
            (new LogActivity())->addToLog($standing->id,$request);

        }catch(\Throwable $th) {
            return $this->errorResponse($th->getMessage(),500);
        }

        return $this->showAny($standing);

    }
}
