<?php


namespace App\Lib;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

trait ResponseGeneralization {


    private function successResponse($data,$message,$code){


          return response()->json([

            'data' => $data,
            'message' => $message
          ],$code);
    }


    protected function errorResponse($message,$code){

        return response()->json([

            'error' => $message,
            'code' => $code
        ],$code);

    }


    protected function showAll($collection,int $code = 200, $message = null) : JsonResponse{

        return $this->successResponse($collection,$message,$code);

    }


    protected function showOne(Model $model,string $message,int $code = 200) : JsonResponse{


        return $this->successResponse($model,$message,$code);


    }


}
