<?php

namespace App\Helpers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Stevebauman\Location\Facades\Location;
use App\Http\Controllers\Api\ApiController;
use App\Models\LogActivity as LogActivityModel;

class LogActivity extends ApiController
{

    public  function addToLog($subjectId, $request = null)
    {

        $log = [];

        if ($request) {
            $data = explode('@', Route::currentRouteAction());
            $log['subject'] = $data[1];
            $log['subject_id'] = $subjectId;
            $log['url'] = $request->fullUrl();
            $log['method'] = $request->method();
            $log['ip'] = $request->ip() !== "102.50.247.53" ? $request->ip() : "102.50.247.53";
            if (Location::get($log['ip'])) {
                $log['country'] = $request->ip() !== "102.50.247.53" ? Location::get($log['ip'])->countryName : "Casablanca - Multilist";
                $log['region'] = $request->ip() !== "102.50.247.53" ? Location::get($log['ip'])->regionName : "Casablanca - Multilist";
            }
            $log['agent'] = $request->header('user-agent');
            $log['user_id'] = auth()->check() ? auth()->user()->id : (getCurrentUser() ? getCurrentUser()->id : 0);

            LogActivityModel::create($log);
        } else {

            return $this->errorResponse('la requete est non défini', 404);
        }
    }

    public  function logActivityList()
    {

        return $this->showAny(LogActivityModel::all());
    }

    public function lastWeek()
    {
        return $this->showAny(LogActivityModel::all()->where('created_at', '<=', Carbon::now()->addDays(7)));
    }

    public function lastMonth()
    {
        return $this->showAny(LogActivityModel::all()->where('created_at', '<=', Carbon::now()->month));
    }

    public function logActivitiesByUid($uid)
    {
        return $this->showAny(LogActivityModel::all()->where('user_id', '=', $uid));
    }
}
