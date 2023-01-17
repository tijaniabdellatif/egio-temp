<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\AnnounceNotifications;
use App\Notifications\TransactionsNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Notification as Notif;

class NotificationController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // filter
    public function filter(Request $request)
    {

        // build query using $data
        $query = Notif::select('*')->orderBy('created_at', 'desc');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'notifiable_id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'data' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'created_at' => [
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

    public function sendAnnounceNotification(Request $request)
    {
        $userSchema = User::first();
        $announceData = [
            'name' => auth()->user()->username,
            'uid' => auth()->user()->id,
            'body' => 'test',
            'thanks' => 'Thank you',
            'announceText' => 'Check out your announce',
            'announceUrl' => url('/'),
            'announce_id' => 7
        ];

        $announceNotify = new AnnounceNotifications($announceData);

        Notification::send($userSchema, $announceNotify);

        return $this->showAny($announceData, 200, 'Notify Task Completed');
    }



    public function sendTransactionNotification(Request $request)
    {
        $userSchema = User::find(1);
        $TransactionData = [
            'name' => 'test',
            'uid' => 3,
            'body' => 'Transaction success.',
            'thanks' => 'Thank you',
            'transactionText' => 'Check out your Transaction',
            'transactionUrl' => url('/'),
            'transaction_id' => 7
        ];

        $TransactionNotify = new TransactionsNotification($TransactionData);
        $nofity = Notification::send($userSchema, $TransactionNotify);
    }

    public function getNotifications()
    {
        $user = User::first();
        $notifications = $user->notifications->take(4);
        return $this->showAny($notifications, 200, 'success');
    }

    public function getAllNotifications()
    {
        $user = User::first();
        $notifications = $user->notifications;
        return $this->showAny($notifications, 200, 'success');
    }

    public function getUnreadNotifications()
    {
        $user = User::first();
        $notifications = $user->unreadNotifications->take(4);
        // foreach ($user->unreadNotifications as $unreadNotification) {
        //     $unreadNotification->markAsRead();
        // }
        return $this->showAny($notifications, 200, 'success');
    }

    public function markAllNotificationsAsRead()
    {
        $user = User::first();
        $mark = $user->unreadNotifications()->update(['read_at' => now()]);
        if ($mark) {
            return $this->showAny($mark, 200, 'Toutes les notifications sont marquées lues!');
        }
    }

    public function markNotif($id)
    {
        $notif = Notif::where('id', '=', $id)->update(['read_at' => now()]);
        if ($notif) {
            return $this->showAny($notif, 200, 'Nottification marquée lue!');
        } else {
            return 'failed to mark notification as read!';
        }
    }

    public function clearAll()
    {
        $user = User::first();
        $clear = $user->notifications()->delete();
        if ($clear) {
            return $this->showAny($clear, 200, 'Toutes les notifications sont supprimées !');
        }
    }
}