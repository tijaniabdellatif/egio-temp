<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \App\Models\banners;
use Illuminate\Support\Facades\DB;

class bannersController extends ApiController
{
    // filter query
    public function filter(Request $request)
    {
        // build query using $data
        $query = banners::select('*')
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
                'position' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'html_code' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'active' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ]
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

    public function edit(Request $request){
        try{
            $id = $request->id;
            $check = banners::where('id',$id)->update([
                'html_code' => $request->html_code,
                'active' => $request->active
            ]);
            if(!$check){
                return $this->errorResponse('Operation faild',409);
            }
            else{
                return $this->showAny($id);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),500);
        }
    }
}
