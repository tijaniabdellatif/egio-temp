<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\LogRegistry;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Models\LogActivity as ModelsLogActivity;
use Illuminate\Http\Request;


class LogRegistryController extends ApiController
{

    public function __construct()
    {
        return $this->autoDeleteLastMothEntries();
    }

    // filter
    public function filter(Request $request)
    {

        // build query using $data
        $query = ModelsLogActivity::select('log_activities.*', 'users.username as username')
            ->join('users', 'log_activities.user_id', '=', 'users.id')
            ->orderBy('log_activities.created_at', 'desc');


            $hours = '12:00';


        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'log_activities.id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'log_activities.username' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'log_activities.subject' => [
                    "type" => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'log_activities.subject_id' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'log_activities.url' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'log_activities.ip' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'log_activities.region' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'log_activities.created_at' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE', '>', '<'],
                ],
                'hours' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE', '>', '<'],
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


            return $this->showAny($result);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    public function logActivity()

    {

        $activity = (new LogActivity())->logActivityList();

        $data = $activity->getData();

        $logs = [];
        foreach ($data as $dat) {
            $id = $dat[0]->user_id;
            //    $user = User::find($id)->makeHidden(['password'])->with('activity')->get();

            $columns = ['log_activities.id', 'username', 'firstname', 'lastname', 'subject', 'subject_id', 'method', 'url', 'region', 'agent', 'lat', 'long', 'log_activities.created_at'];
            $user = DB::table('users')
                ->join('log_activities', 'user_id', "=", "users.id")
                ->get($columns);

            // return($user['0']);
            return ($user);
        }
    }

    public function LastWeekLogs()
    {
        return (new LogActivity())->lastWeek();
    }

    public function LastMonthLogs()
    {
        return (new LogActivity())->lastMonth();
    }

    public function logActivitiesByUid($Uid)
    {
        return (new LogActivity())->logActivitiesByUid($Uid);
    }

    public function InsertLog($action, $description)
    {
        $Log = new LogRegistry();
        $Log->uid = Auth::user()->id;
        $Log->uid = Auth::user()->username;
        $Log->action = $action;
        $Log->description = $description;
        if ($Log->save()) {
            return $this->showAny($Log, 200, 'Success');
        } else {
            return false;
        }
    }

    public function autoDeleteLastMothEntries()
    {
        LogRegistry::where('date', '<', Carbon::now()
            ->subMonth())
            ->where('action', '=', 'login')
            ->each(function ($item) {

                $item->delete();
            });
    }
}
