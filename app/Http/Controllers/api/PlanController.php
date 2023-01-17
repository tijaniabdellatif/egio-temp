<?php

namespace App\Http\Controllers\api;

use App\Models\Plan;
use App\Models\Contract;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;
use App\Notifications\AnnounceNotifications;
use Illuminate\Support\Facades\Notification;

class PlanController extends ApiController
{

    // filter
    public function filter(Request $request){

        // build query using $data
        $query = \App\Models\Plan::select('id','ads_nbr','ltc_nbr','price','duration','description')
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
                'ads_nbr' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'ltc_nbr' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'price' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'duration' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'description' => [
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
            // return response()->json([
            //     'status' => 'success',
            //     'data' => $result
            // ]);
            return $this->showAny($result);

        } catch (\Exception $e) {
            // return response()->json(['error' => 'Check your columns or tables names'], 500);
            return $this->errorResponse($e->getMessage(), 500);
        }


    }

    // create a new plan
    public function createPlan(Request $request){

        // validate request
        $validator = Validator::make($request->all(), [
            'ads_nbr' => 'required|integer',
            'ltc_nbr' => 'required|integer',
            'price' => 'required|integer',
            'duration' => 'required|integer'
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 401);
            return $this->errorResponse($validator->errors(), 401);
        }

        // create a new plan
        $plan = new \App\Models\Plan();

        // set plan data
        $plan->ads_nbr = $request->ads_nbr;
        $plan->ltc_nbr = $request->ltc_nbr;
        $plan->price = $request->price;
        $plan->duration = $request->duration;
        $plan->description = $request->description;

        // save plan
        $save = $plan->save();
        if($save){
            $data = [

                'name' => auth()->user()->name,
                'uid' => auth()->user()->id,
                'body' => auth()->user()->id.' a crée un nouveau plan, prix : '.$plan->price .' durée : '.$plan->duration. ' jours',
                'text' => 'Visualiser les plans',
                'url' => url('/admin/items'),
                'subject_id' => $plan->id,
                'notification_flag' => 'info'

        ];
            Notification::send(auth()->user(), new AnnounceNotifications($data));
            (new LogActivity())->addToLog($plan->id,$request);


        }

        // return success message with data
        // return response()->json([
        //     'status' => 'success',
        //     'data' => $plan
        // ]);
        return $this->showAny($plan);

    }

    // update a plan
    public function updatePlan(Request $request){

        // validate request
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'ads_nbr' => 'integer',
            'ltc_nbr' => 'integer',
            'price' => 'integer',
            'duration' => 'integer'
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 401);
            return $this->errorResponse($validator->errors(), 401);
        }

        // get plan
        $plan = \App\Models\Plan::find($request->id);

        // check if plan exists
        if(!$plan){
            // return response()->json(['error' => 'Plan not found'], 404);
            return $this->errorResponse('Plan not found', 404);
        }

        // set plan data
        $plan->ads_nbr = $request->ads_nbr;
        $plan->ltc_nbr = $request->ltc_nbr;
        $plan->price = $request->price;
        $plan->duration = $request->duration;
        $plan->description = $request->description;

        // save plan
        $save = $plan->save();
        if($save){
            $data = [

                'name' => auth()->user()->name,
                'uid' => auth()->user()->id,
                'body' => auth()->user()->id.' a modifié le plan : '. $request->id.' nouveau prix : '.$plan->price .' la durée : '.$plan->duration. ' jours',
                'text' => 'Check out your plans',
                'url' => url('/admin/items'),
                'subject_id' => $request->id,
                'notification_flag' => 'info'

        ];
            Notification::send(auth()->user(), new AnnounceNotifications($data));
            (new LogActivity())->addToLog($plan->id,$request);

        }

        // return success message with data
        // return response()->json([
        //     'status' => 'success',
        //     'data' => $plan
        // ]);
        return $this->showAny($plan);

    }

    // Delete a plan
    public function deletePlan(Request $request, Plan $plan) {

        try {
            $plan = Plan::find($request->id);

            if(!$plan){
                return $this->errorResponse('Plan not found',404);
            }

            $check = Contract::where('plan_id','=',$request->id)->exists();
            if($check) {
                return $this->errorResponse('Ce plan est déjà utilisé !',404);
            }

            $plan->delete();
            (new LogActivity())->addToLog($plan->id,$request);

        }catch(\Throwable $th) {
            return $this->errorResponse($th->getMessage(),500);
        }

        return $this->showAny($plan);

    }

}
