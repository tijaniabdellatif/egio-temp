<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\ads;
use App\Models\clicks;
use App\Models\email;
use App\Models\LogActivity;
use App\Models\Transaction;
use \App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends ApiController
{

    function getTotalAds(Request $request)
    {
        if ($request->days) $result = ads::where('created_at', '>=', Carbon::now()->subDays($request->days))->count();
        else $result = ads::count();
        return $this->showAny($result);
    }

    function getTotalPublishedAds(Request $request)
    {
        if ($request->days) $result = ads::where('status', '=', '10')->where('created_at', '>=', Carbon::now()->subDays($request->days))->count();
        else $result = ads::where('status', '=', '10')->count();
        return $this->showAny($result);
    }

    function getAdsByUnivers(Request $request)
    {
        if ($request->id) $result = ads::leftJoin('cats', 'cats.id', '=', 'ads.catid')->where('cats.parent_cat', '=', $request->id)->count();
        else $result = 0;
        return $this->showAny($result);
    }

    function getAdsByUser(Request $request)
    {
        if ($request->id) $result = ads::leftJoin('users', 'users.id', '=', 'ads.id_user')->where('users.usertype', '=', $request->id)->count();
        else $result = 0;
        return $this->showAny($result);
    }

    function getTotalInReviewAds(Request $request)
    {
        if ($request->days) $result = ads::where('status', '=', '30')->where('created_at', '>=', Carbon::now()->subDays($request->days))->count();
        else $result = ads::where('status', '=', '30')->count();
        return $this->showAny($result);
    }

    function getTotalUsers(Request $request)
    {
        if ($request->days) $result = User::where('created_at', '>=', Carbon::now()->subDays($request->days))->count();
        else $result = User::count();
        return $this->showAny($result);
    }


    function getTotalAdsByMonths(Request $request)
    {
        $data = ads::select(DB::raw('count(id) as `data`'), DB::raw('YEAR(created_at) year, MONTH(created_at) month'))->where('status', '=', '10')
            ->groupby('year', 'month')->take(12)->orderby('created_at','DESC')
            ->get();
        return $this->showAny($data);
    }






    function ClientsFilter(Request $req)
    {
        // build query using $data
        $query = \App\Models\User::select(
            'users.id',
            'users.status',
            'ced.username as assigned_ced',
            'commercial.username as assigned_commercial',
            DB::raw('CONCAT(users.firstname," ",users.lastname) as fullname'),
            'pro_user_info.company',
            'users.created_at',
            'cities.name as city',
            'users.usertype',
            'user_type.designation as u_type',
            'plan_catalogue.description as contract_name',
            'contracts.date as start_date',
            DB::raw('TIMESTAMPADD(DAY,contracts.duration ,contracts.date) as end_date'),
            'contracts.ads_nbr as permitted_ads',
            DB::raw('IFNULL(totalAds.count,0) as total_ads'),
            DB::raw('IFNULL(activeAds.count,0) as active_ads'),
            DB::raw('(IFNULL(in_ltc.total,0) + IFNULL(out_ltc.total,0)) as init_ltc'),
            DB::raw('IFNULL(option_ltc.total,0) as used_ltc'),
            'users.coins as balance',
            DB::raw('IFNULL(vClicks.views,0) as views'),
            DB::raw('IFNULL(pClicks.phones,0) as phones'),
            DB::raw('IFNULL(wClicks.wtsps,0) as wtsps'),
            DB::raw('IFNULL(eClicks.emails,0) as emails')
        )
            ->leftJoin('users as ced', 'users.assigned_ced', '=', 'ced.id')
            ->leftJoin('user_type', 'users.usertype', '=', 'user_type.id')
            ->leftJoin('pro_user_info', 'users.id', '=', 'pro_user_info.user_id')
            ->leftJoin('cities', 'pro_user_info.city', '=', 'cities.id')
            ->leftJoin('contracts', 'contracts.user_id', '=', 'users.id')
            ->leftJoin('users as commercial', 'contracts.assigned_user', '=', 'commercial.id')
            ->leftJoin('plan_catalogue', 'contracts.plan_id', '=', 'plan_catalogue.id')
            ->leftJoinSub(ads::select('id_user', DB::raw("COUNT(id) as 'count'"))->groupBy('id_user'), 'totalAds', function ($q) {
                $q->on('totalAds.id_user', '=', 'users.id');
            })
            ->leftJoinSub(ads::select('id_user', DB::raw("COUNT(id) as 'count'"))->where("status", "=", "10")->groupBy('id_user'), 'activeAds', function ($q) {
                $q->on('activeAds.id_user', '=', 'users.id');
            })
            ->leftJoinSub(
                Transaction::select('user_id', DB::raw("SUM(amount) as 'total'"))
                    ->where("amount", ">", 0)->where(function ($q) {
                        $q->where('type', "=", "new_contract")->orWhere('type', "=", "deposit_transaction");
                    })->groupBy('user_id'),
                'in_ltc',
                function ($q) {
                    $q->on('in_ltc.user_id', '=', 'users.id');
                }
            )
            ->leftJoinSub(
                Transaction::select('user_id', DB::raw("SUM(amount) as 'total'"))
                    ->where("amount", "<", 0)->where(function ($q) {
                        $q->where('type', "=", "withdrawal_transaction");
                    })->groupBy('user_id'),
                'out_ltc',
                function ($q) {
                    $q->on('out_ltc.user_id', '=', 'users.id');
                }
            )
            ->leftJoinSub(
                Transaction::select('user_id', DB::raw("SUM(amount) as 'total'"))
                    ->where("amount", "<", 0)->where(function ($q) {
                        $q->where('type', "=", "option_transaction");
                    })->groupBy('user_id'),
                'option_ltc',
                function ($q) {
                    $q->on('option_ltc.user_id', '=', 'users.id');
                }
            )
            ->leftJoinSub(
                clicks::select('ads.id_user', DB::raw("COUNT(clicks.id) as 'views'"))->join('ads', 'clicks.ad_id', '=', "ads.id")
                    ->where("type", "=", "hit")->groupBy('ads.id_user'),
                'vClicks',
                function ($q) {
                    $q->on('vClicks.id_user', '=', 'users.id');
                }
            )
            ->leftJoinSub(
                clicks::select('ads.id_user', DB::raw("COUNT(clicks.id) as 'phones'"))->join('ads', 'clicks.ad_id', '=', "ads.id")
                    ->where("type", "=", "phone")->groupBy('ads.id_user'),
                'pClicks',
                function ($q) {
                    $q->on('vClicks.id_user', '=', 'users.id');
                }
            )
            ->leftJoinSub(
                clicks::select('ads.id_user', DB::raw("COUNT(clicks.id) as 'wtsps'"))->join('ads', 'clicks.ad_id', '=', "ads.id")
                    ->where("type", "=", "wtsp")->groupBy('ads.id_user'),
                'wClicks',
                function ($q) {
                    $q->on('vClicks.id_user', '=', 'users.id');
                }
            )
            ->leftJoinSub(
                email::select('ads.id_user', DB::raw("COUNT(emails.id) as 'emails'"))->join('ads', 'emails.ad_id', '=', "ads.id")
                    ->groupBy('ads.id_user'),
                'eClicks',
                function ($q) {
                    $q->on('eClicks.id_user', '=', 'users.id');
                }
            )
            ->where(function ($q) {
                $q->where('users.usertype', '=', '3')->orWhere('users.usertype', '=', '4');
            })
            ->groupBy('users.id')
            ->orderBy('users.id', 'desc');

        // the filter helper function
        $query = queryFilter(
            $req->where, // json data
            $query,
            [
                // 'user_info' => [
                //     'type' => 'leftJoin',
                //     'foreign_key' => 'users.id',
                //     'primary_key' => 'user_info.id_user',
                //     'op' => '='
                // ]
            ], // joins
            [
                'users.id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'users.username' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'pro_user_info.company' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'users.email' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'cities.id' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'users.created_at' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE', '>', '<'],
                ],
                'users.usertype' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'users.status' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ced.id' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'commercial.id' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ]
            ], // allowed cols to filter by
            true // passing data as json (if true) or php array (if false)
        );

        try {

            $result = [];
            //check if query has sort and order
            if (isset($req->sort) && isset($req->order)) {
                $query = $query->orderBy($req->sort, $req->order);
            }

            // limit
            if (isset($req->limit)) {
                $query = $query->take($req->limit);
            }

            //check if request has per_page parameter
            if ($req->has('per_page')) {
                // get the data
                $result = $query->paginate($req->per_page);
            } else {
                // get the data
                $result = $query->get();
            }

            // get sql statement
            // $sql = $query->toSql();
            // dd($sql);

            // ddQuery($query);

            // return success message with data
            // return response()->json([
            //     'status' => 'success',
            //     'data' => $result
            // ]);
            return $this->showAny($result);
        } catch (\Exception $e) {
            // return response()->json(['error' => 'Check your columns or tables names'], 400);
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
