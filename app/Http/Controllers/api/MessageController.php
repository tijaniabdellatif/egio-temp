<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends ApiController
{
    //

    // filter
    public function filter(Request $req)
    {

        // id
        // sender
        // receiver
        // message
        // status

        // build query using $data
        $query = \App\Models\Message::select('*')
            ->orderBy('id', 'desc');

        // the filter helper function
        $query = queryFilter(
            $req->where, // json data
            $query,
            [], // joins
            [
                'id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'sender' => [
                    'type' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'receiver' => [
                    'type' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'message' => [
                    'type' => 'string',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'status' => [
                    'type' => 'string',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ]
            ], // allowed cols to filter by
            true // passing data as json (if true) or php array (if false)
        );

        try {
            $result = [];
            //check if query has sort and order
            if (isset($req->sort) && isset($req->order)) {
                $result = $query->orderBy($req->sort, $req->order);
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
