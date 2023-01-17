<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Transaction;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TransactionsNotification;

class TransactionController extends ApiController
{

    // filter function
    public function filter(Request $request)
    {

        // build query using $data
        $query = Transaction::select('*')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->orderBy('transactions.id', 'desc');

        // id
        // user_id
        // amount
        // type
        // notes
        // datetime

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'user_id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'amount' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', '>', '<', '>=', '<='],
                ],
                'type' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'notes' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'datetime' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE', '>', '<'],
                ],
                'users.username' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],


            ] // allowed cols to filter by
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

            // ddQuery($query);

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

    // create new transaction
    public function createNewTransaction(Request $request)
    {
        // id
        // user_id
        // amount
        // notes

        // validate request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'amount' => 'required|integer|not_in:0',
            'notes' => 'nullable|string',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return $this->errorResponse($validator->errors(), 400);
        }

        $coinsManager = new \App\Lib\CoinsManager;

        // type withdrawal_transaction or deposit_transaction based on the amount
        $type = $request->amount > 0 ? 'Nouveau dépôt' : 'Retrait';

        try {
            // create transaction
            $transaction = $coinsManager->transaction(
                $request->user_id,
                $request->amount,
                $type,
                $request->notes ?? 'Nouvelle Transaction',
                function ($tansaction) {
                    return true;
                }
            );
        } catch (\Exception $e) {
            // return response()->json(['error' => $e->getMessage()], 500);
            return $this->errorResponse($e->getMessage(), 500);
        }


        // check if transaction was created
        if (!$transaction) {
            // return response()->json(['error' => 'Transaction was not created'], 400);
            return $this->errorResponse('Transaction was not created', 400);
        } else {


            $userSchema = User::first();
            $TransactionData = [
                'name' => auth()->user()->username,
                'body' => auth()->user()->username . ' a modifié la transaction : ' . $transaction->id . ' le montant ajouté : ' . $request->amount,
                'text' => 'Visualisez la transaction',
                'url' => url('/'),
                'subject_id' => $transaction->id,
                'amount' => $request->amount,
                'notification_flag' => 'success'
            ];

            $TransactionNotify = new TransactionsNotification($TransactionData);
            Notification::send($userSchema, $TransactionNotify);

            (new LogActivity())->addToLog($request->user_id, $request);

            return $this->showAny($transaction);
        }
    }
}
