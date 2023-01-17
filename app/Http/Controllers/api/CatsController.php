<?php

namespace App\Http\Controllers\api;

use App\Models\ads;
use \App\Models\cats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;

class CatsController extends ApiController
{

    // filter
    public function filter(Request $request){

        // build query using $data
        $query = Cats::select('cats.*','parent.title as parent')
                    ->join('cats AS parent', 'parent.id', '=', 'cats.parent_cat')
                    ->whereNotNull('cats.parent_cat');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'cats.id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'cats.title' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'cats.parent_cat' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'cats.status' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'cats.type' => [
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

    public function getParents(Request $request)
    {
        $data = DB::select('CALL getParentCats');
        return $this->showAny($data);
    }

    // create a new plan
    public function create(Request $request){

        try{
            // validate request
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:100',
                'parent_cat' => 'required|integer',
                'type' => 'required|string|max:10',
                'status' => 'required|string',
                'keywords' => 'nullable|string',
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // create a new plan
            $cat = new cats();

            // set plan data
            $cat->title = $request->title;
            $cat->parent_cat = $request->parent_cat;
            $cat->type = $request->type;
            $cat->status = $request->status;
            $cat->is_project = $request->is_project?1:0;
            $cat->keywords = $request->keywords;

            // save plan
            $cat->save();

            $newData = Cats::select('cats.*','parent.title as parent')
                    ->join('cats AS parent', 'parent.id', '=', 'cats.parent_cat')
                    ->where('cats.id','=',$cat->id)->first();

            return $this->showAny($newData);
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
                'title' => 'required|string|max:100',
                'parent_cat' => 'required|integer',
                'type' => 'required|string|max:10',
                'status' => 'required|string',
                'keywords' => 'nullable|string',
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // get cat
            $cat = cats::find($request->id);

            // check if plan exists
            if(!$cat){
                return $this->errorResponse('Cat not found',404);
            }

            // set plan data
            $cat->title = $request->title;
            $cat->parent_cat = $request->parent_cat;
            $cat->type = $request->type;
            $cat->status = $request->status;
            $cat->keywords = $request->keywords;
            if(!$cat->isDirty()){
                return $this->errorResponse('Rien a modifier',409);
            }
            // save plan
            $cat->save();

            $newData = Cats::select('cats.*','parent.title as parent')
                        ->join('cats AS parent', 'parent.id', '=', 'cats.parent_cat')
                        ->where('cats.id','=',$cat->id)->first();

            return $this->showAny($newData);
        }catch(\Throwable $th){
            return ['success'=>false,'er'=>$th->getMessage()];
            return $this->errorResponse($th->getMessage(),500);
        }

    }

    // To delete categorie
    public function destroy(Request $request, Cats $cat) {

        try {
            $cat = Cats::find($request->id);

            if(!$cat){
                return $this->errorResponse('Categorie not found',404);
            }

            // Check if cat is associated with an ad before delete
            $check = ads::where('catid', '=',$request->id)->exists();

            if($check) {
                return $this->errorResponse('Catégorie déjà utilisée !',401);

            }

            $cat->delete();
        }catch(\Throwable $th) {
            return $this->errorResponse($th->getMessage(),500);
        }

        return $this->showAny($cat);

    }

}
