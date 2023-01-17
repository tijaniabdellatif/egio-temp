<?php

namespace App\Http\Controllers\api;



use \App\Models\option_type;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use \App\Models\options_catalogue;
use \App\Models\options;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;
use App\Notifications\AnnounceNotifications;
use Illuminate\Support\Facades\Notification;

class optionsController extends ApiController
{
    // filter
    public function filter(Request $request)
    {

        // build query using $data
        $query = options_catalogue::select('options_catalogue.*', 'option_type.designation as type')
            ->join('option_type', 'option_type.id', '=', 'options_catalogue.type_id');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'options_catalogue.id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'options_catalogue.designation' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'options_catalogue.price' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'options_catalogue.type_id' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'options_catalogue.duration' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
            ], // allowed cols to filter by
            true // passing data as json (if true) or php array (if false)
        );

        try {
            $result = [];
            //check if query has sort and order
            if (isset($request->sort) && isset($request->order)) {
                $result = $query->orderBy($request->sort, $request->order);
            }

            //check if request has per_page parameter
            if ($request->has('per_page')) {
                // get the data
                $result = $query->paginate($request->per_page);
            } else {
                // get the data
                $result = $query->get();
            }

            // get sql statement
            // $sql = $query->toSql();
            // dd($sql);

            // return success message with data
            return $this->showAny($result);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function getTypes(Request $request)
    {
        //$data = option_type::all();
        $data = DB::select('CALL getTypes()');
        return $this->showAny($data);
    }

    // create a new plan
    public function createOption(Request $request)
    {

        try {
            // validate request
            $validator = Validator::make($request->all(), [
                'designation' => 'required|string|max:100',
                'price' => 'required|numeric|gt:0',
                'type_id' => 'required|integer',
                'duration' => 'required|integer|gt:0',
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 409);
            }

            // create a new plan
            $option = new options_catalogue();

            // set plan data
            $option->designation = $request->designation;
            $option->price = $request->price;
            $option->type_id = $request->type_id;
            $option->duration = $request->duration;

            // save plan
            $option->save();

            // Add action to logs
            (new LogActivity())->addToLog($option->id, $request);


            return $this->showAny($option);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    // update a plan
    public function updateOption(Request $request)
    {

        try {
            // validate request
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'designation' => 'required|string|max:100',
                'price' => 'required|numeric|gt:0',
                'type_id' => 'required|integer',
                'duration' => 'required|integer|gt:0',
            ]);

            // check if validation fails
            if ($validator->fails()) {


                return $this->errorResponse($validator->errors(), 409);
            }

            // get cat
            $option = options_catalogue::find($request->id);

            // check if plan exists
            if (!$option) {
                return $this->errorResponse('option not found', 404);
            }

            // set plan data
            $option->designation = $request->designation;
            $option->price = $request->price;
            $option->type_id = $request->type_id;
            $option->duration = $request->duration;
            if (!$option->isDirty()) {
                return $this->errorResponse('Rien a modifier', 409);
            }
            // save plan
            $save = $option->save();
            if ($save) {

                $data = [

                    'name' => auth()->user()->name,
                    'uid' => auth()->user()->id,
                    'body' => 'L\'option ' . $option->id . ' a été modifiée',
                    'thanks' => 'Thank you',
                    'text' => 'Vérifier les options',
                    'url' => url('/admin/items'),
                    'subject_id' => $option->type_id,
                    'notification_flag' => 'success'

                ];
                Notification::send(auth()->user(), new AnnounceNotifications($data));

                // Ad action to Logs
                (new LogActivity())->addToLog($option->id, $request);
            }

            $newData = options_catalogue::select('options_catalogue.*', 'option_type.designation as type')
                ->join('option_type', 'option_type.id', '=', 'options_catalogue.type_id')
                ->where('options_catalogue.id', '=', $option->id)->first();

            $option->save();
            return $this->showAny($newData);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    // Delete a plan
    public function deleteOption(Request $request, options_catalogue $option)
    {

        try {
            $options_catalogue = options_catalogue::find($request->id);

            if (!$options_catalogue) {
                return $this->errorResponse('Option not found', 404);
            }

            // Check if option exists in option to abort delete
            $check = options::where('option_id', '=', $request->id)->exists();
            if ($check) {
                return $this->errorResponse('Option déjà utilisée !', 401);
            }


            $options_catalogue->delete();

            //Add action to Logs
            (new LogActivity())->addToLog($options_catalogue->id, $request);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }

        return $this->showAny($options_catalogue);
    }
}