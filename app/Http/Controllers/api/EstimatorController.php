<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\SimpleMail;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class EstimatorController extends ApiController
{

    public function sendEmailEstimation(Request $request){

                try {

                    $validator = Validator::make($request->all(), [
                        'email' => 'required|email',
                        'firstname' => 'required',
                        'lastname' => 'required',
                        'phone' => 'required',
                    ]);

                    if($validator->fails()){

                        return $this->errorResponse($validator->errors(), 400);
                    }

                    $host_name = $_SERVER['HTTP_HOST'];

                    Mail::send(new SimpleMail(
                        [
                            "to" => $request->email,
                            "subject" => "Votre estimation est prête !",
                            "view" => "emails.Estimation.fr",
                            "data" => [
                                'firstname' => $request->firstname,
                                'lastname' => $request->lastname,
                                'phone' => $request->phone,
                                'host_name' =>  $host_name
                            ]
                        ]
                    ));



                }catch (\Throwable $th) {

                    return $this->errorResponse($th->getMessage(), 500);
                }
    }
}
