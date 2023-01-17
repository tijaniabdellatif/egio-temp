<?php

namespace App\Lib;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait ApiResponser {

    // to return a success response with data
    private function successResponse(array $data, $code){
        return response()->json($data, $code);
    }

    // to return an error response with data
    protected function errorResponse($message, $code){
        return response()->json([
            "error" => $message,
            "status" => "error",
            "success" => false
        ],$code);
    }

    // return success any data
    protected function showAny($data = null, $code = 200, $message = null){
        return $this->successResponse([
            "data" => $data,
            "message" => $message,
            "status" => "success",
            "success" => true
        ],$code);
    }

    protected function showData(Collection $data, $code = 200){
        return $this->showAny($data, $code);
    }

}
