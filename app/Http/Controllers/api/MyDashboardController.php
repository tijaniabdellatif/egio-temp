<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ads;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\clicks;
use App\Models\Contract;
use App\Models\email;

class MyDashboardController extends ApiController
{
    function getGeneralInfos(Request $request)
    {
        $userId = $request->id;
        $user = User::select('usertype', 'coins')->where('id', '=', $userId)->first();

        $usertype = $user ? $user->usertype : 0;
        $balance = $user ? $user->coins : 0;
        $totalAds = ads::where('status', '<>', '70')->where('id_user', '=', $userId)->count();
        $publishedAds = ads::where('status', '=', '10')->where('id_user', '=', $userId)->count();
        $draftAds = ads::where('status', '=', '20')->where('id_user', '=', $userId)->count();
        $inReviewAds = ads::where('status', '=', '30')->where('id_user', '=', $userId)->count();
        $rejectedAds = ads::where('status', '=', '50')->where('id_user', '=', $userId)->count();
        $contracts = Contract::select("contracts.ads_nbr","contracts.date","contracts.duration","plan_catalogue.description")->join('plan_catalogue','plan_catalogue.id','=','contracts.plan_id')->where('contracts.user_id', '=', $userId)->where('contracts.active', '=', '1')->orderBy('contracts.id', 'DESC')->first();

        $result = [
            "balance" => $balance,
            "usertype" => $usertype,
            "totalAds" => $totalAds,
            "publishedAds" => $publishedAds,
            "draftAds" => $draftAds,
            "inReviewAds" => $inReviewAds,
            "rejectedAds" => $rejectedAds,
            "contracts" => $contracts,
        ];
        return $this->showAny($result);
    }

    function getStatistics(Request $request)
    {
        $userId = $request->id;

        if ($request->days) {
            $views = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where('clicks.type', '=', 'hit')->where('ads.id_user', '=', $userId)
                ->where('clicks.date', '>=', Carbon::now()->subDays($request->days))->count();
            $phones = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where('clicks.type', '=', 'phone')->where('ads.id_user', '=', $userId)
                ->where('clicks.date', '>=', Carbon::now()->subDays($request->days))->count();
            $wtsps = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where('clicks.type', '=', 'wtsp')->where('ads.id_user', '=', $userId)
                ->where('clicks.date', '>=', Carbon::now()->subDays($request->days))->count();
            $emails = email::join('ads', 'ads.id', '=', 'emails.ad_id')->where('emails.status', '=', '10')->where('ads.id_user', '=', $userId)
                ->where('emails.date', '>=', Carbon::now()->subDays($request->days))->count();
        } else {
            $views = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where('clicks.type', '=', 'hit')->where('ads.id_user', '=', $userId)->count();
            $phones = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where('clicks.type', '=', 'phone')->where('ads.id_user', '=', $userId)->count();
            $wtsps = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where('clicks.type', '=', 'wtsp')->where('ads.id_user', '=', $userId)->count();
            $emails = email::join('ads', 'ads.id', '=', 'emails.ad_id')->where('emails.status', '=', '10')->where('ads.id_user', '=', $userId)->count();
        }

        $result = [
            "views" => $views,
            "phones" => $phones,
            "wtsps" => $wtsps,
            "emails" => $emails,
        ];
        return $this->showAny($result);
    }

    function getViews(Request $request)
    {
        $userId = $request->id;
        if ($request->days) $result = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where('clicks.type', '=', 'hit')->where('ads.id_user', '=', $userId)
            ->where('clicks.date', '>=', Carbon::now()->subDays($request->days))->count();
        else $result = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where('clicks.type', '=', 'hit')->where('ads.id_user', '=', $userId)->count();
        return $this->showAny($result);
    }

    function getWtsps(Request $request)
    {
        $userId = $request->id;
        if ($request->days) $result = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where('clicks.type', '=', 'wtsp')->where('ads.id_user', '=', $userId)
            ->where('clicks.date', '>=', Carbon::now()->subDays($request->days))->count();
        else $result = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where('clicks.type', '=', 'wtsp')->where('ads.id_user', '=', $userId)->count();
        return $this->showAny($result);
    }

    function getPhones(Request $request)
    {
        $userId = $request->id;
        if ($request->days) $result = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where('clicks.type', '=', 'phone')->where('ads.id_user', '=', $userId)
            ->where('clicks.date', '>=', Carbon::now()->subDays($request->days))->count();
        else $result = clicks::join('ads', 'ads.id', '=', 'clicks.ad_id')->where('clicks.type', '=', 'phone')->where('ads.id_user', '=', $userId)->count();
        return $this->showAny($result);
    }

    function getEmails(Request $request)
    {
        $userId = $request->id;
        if ($request->days) $result = email::join('ads', 'ads.id', '=', 'emails.ad_id')->where('emails.status', '=', '10')->where('ads.id_user', '=', $userId)
            ->where('emails.date', '>=', Carbon::now()->subDays($request->days))->count();
        else $result = email::join('ads', 'ads.id', '=', 'emails.ad_id')->where('emails.status', '=', '10')->where('ads.id_user', '=', $userId)->count();
        return $this->showAny($result);
    }

    function getLatestEmails(Request $request)
    {
        $userId = $request->id;
        $result = email::select('emails.*', 'ads.title')->join('ads', 'ads.id', '=', 'emails.ad_id')->where('emails.status', '=', '10')
            ->where('ads.id_user', '=', $userId)->orderBy('emails.date', 'DESC')->limit(5)->get();
        return $this->showAny($result);
    }


    public function getProfileInfos($id){

        $user = User::find($id);
        return $this->showAny($user);



    }
}
