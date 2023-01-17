<?php

namespace App\Lib;

use App\Models\User;
use App\Mail\SimpleMail;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;


class CoinsManager
{

    // use callback
    public function transaction($user_id, $amount, $type, $notes, $callback)
    {


        $request = new Request();

        // check if user is exist
        $user = \App\Models\User::find($user_id);

        if (!$user) {
            throw new \Exception("User not found");
            return;
        }

        // check if user has enough amount
        if ($user->coins + $amount < 0) {
            throw new \Exception("User has not enough balance");
            return;
        }

        DB::beginTransaction();

        $transaction = new \App\Models\Transaction;

        $transaction->user_id = $user_id;
        $transaction->amount = $amount;
        $transaction->type = $type;
        $transaction->notes = $notes;
        // get curent date and time and set it to timestamps
        $transaction->datetime = date('Y-m-d H:i:s');

        if ($amount != 0) {

            $transaction->save();

            // transaction not created
            if (!$transaction) {
                DB::rollBack();
                throw new \Exception("Transaction not created");
                return;
            }

            // update user balance
            $user->coins = $user->coins + $transaction->amount;
            $user->save();

            // Get the user mail and username
            $Userinfo = User::select('email', 'username', 'coins')->where('id', '=', $user_id)->first();
            $Amountadded = $transaction->amount;
            $host_name = $_SERVER['HTTP_HOST'];

            Mail::send(new SimpleMail(
                [
                    "to" =>  iconv('ISO-8859-1','UTF-8//IGNORE', filter_var($Userinfo['email'], FILTER_SANITIZE_EMAIL)),
                    "subject" => "Votre solde a été alimenté avec succès !",
                    "view" => "emails.Balance.fr",
                    "data" => [
                        "username" => $Userinfo['username'],
                        "coins" => $Userinfo['coins'],
                        "transaction" => $Amountadded,
                        "host_name" => $host_name
                    ]
                ]
            ));
        }

        $transaction->user_new_balance = $user->coins;

        $check = $callback($transaction);

        if (!$check) {
            DB::rollBack();
            throw new \Exception("Transaction not completed");
            return;
        }


        DB::commit();
        return $transaction;
    }

    // use callback
    public function transaction2($user_id, $amount, $type, $notes, $callback)
    {

        // check if user is exist
        $user = \App\Models\User::find($user_id);

        if (!$user) {
            return [
                "success" => false,
                "code" => 404,
                "message" => "User not found"
            ];
        }

        // check if user has enough amount
        if ($user->coins + $amount < 0) {
            return [
                "success" => false,
                "code" => 401,
                "message" => "User has not enough balance"
            ];
        }

        DB::beginTransaction();

        $transaction = new \App\Models\Transaction;

        $transaction->user_id = $user_id;
        $transaction->amount = $amount;
        $transaction->type = $type;
        $transaction->notes = $notes;
        // get curent date and time and set it to timestamps
        $transaction->datetime = date('Y-m-d H:i:s');

        if ($amount != 0) {

            $transaction->save();

            // transaction not created
            if (!$transaction) {
                DB::rollBack();
                return [
                    "success" => false,
                    "code" => 409,
                    "message" => "Transaction not created"
                ];
            }

            // update user balance
            $user->coins = $user->coins + $transaction->amount;
            $user->save();
        }

        $transaction->user_new_balance = $user->coins;

        $check = $callback($transaction);

        if (!$check) {
            DB::rollBack();

            return [
                "success" => false,
                "code" => 409,
                "message" => "Something wrong!"
            ];
        }

        DB::commit();

        return [
            "success" => true,
            "transaction" => $transaction
        ];
    }
}
