<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ads;
use App\Models\User;
use App\Http\Controllers\Api\ApiController;
use App\Notifications\AnnounceNotifications;
use Illuminate\Support\Facades\Notification;


class ExpiredAds extends ApiController
{
    public $ads;
    public $daysLeft;

    public function __construct()
    {
        $this->ads = new ads;
        $this->daysLeft = Carbon::today()->addDays(7);
    }

    public function sendExpiredAdNotif()
    {

        $expiredAds  = $this->ads->where('expiredate','<=',date('Y-m-d H:i:s'))->where('id_user','=',Auth::id())->get();

        foreach($expiredAds as $expired)
        {
            if($expired->expireNotify){
            Notification::send(auth()->user(), new AnnounceNotifications(
                [
                    'name' => auth()->user()->username,
                    'body' => 'Your ad has been Expired !',
                    'text' => 'Check out your ads',
                    'url' => url('/admin/items'),
                    'subject_id' => $expired->id,
                    'notification_flag' => 'warning',
                ]));
                
                $expired->update(['expireNotify' => false]);
            }

        }
    }

    public function sendAdWillExpiredAdNotif()
    {

        $willExpire  = $this->ads->where('expiredate','<=',$this->daysLeft)->where('id_user','=',1)->get();
        foreach($willExpire as $we)
        {
            Notification::send(User::find(1), new AnnounceNotifications(
                [
                    'name' => 'ss',
                    'body' => 'Your ad will Expire after '.$this->daysLeft->diffForHumans(),
                    'text' => 'Check out your ads',
                    'url' => url('/admin/items'),
                    'subject_id' => $we->id,
                    'notification_flag' => 'warning',
                    'stopNotifications' => true
                ]));

        }
    }
}
