<?php

namespace App\Http\Controllers\api;

use App\Models\ads;
use App\Models\User;
use App\Models\media;
use App\Models\cities;
use App\Mail\SimpleMail;
use App\Models\UserContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;


class EmailController extends ApiController
{

    // filter
    public function filter(Request $req)
    {
        // build query using $data
        $query = \App\Models\email::select('emails.*', 'users.id as user_id', 'users.username', 'users.firstname', 'users.lastname')
            ->leftJoin('ads', 'ads.id', '=', 'emails.ad_id')
            ->leftJoin('users', 'ads.id_user', '=', 'users.id')
            ->orderBy('id', 'desc');

        // the filter helper function
        $query = queryFilter(
            $req->where, // json data
            $query,
            [], // joins
            [
                'id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ad_id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'name' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'phone' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'message' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'emails.status' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'date' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'user_id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'users.username' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'users.firstname' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'users.lastname' => [
                    'type' => 'string',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
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

    // filter query
    public function filterByUser(Request $request)
    {
        // build query using $data
        $query = \App\Models\email::select('emails.*')->join('ads', 'ads.id', '=', 'emails.ad_id')
            ->orderBy('emails.id', 'desc');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'emails.id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'emails.ad_id' => [
                    'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'emails.date' => [
                    'operators' => ['=', '>', '<', '!=', 'LIKE', 'NOT LIKE'],
                ],
                'ads.id_user' => [
                    'operators' => ['=', '>', '<', '!=', 'LIKE', 'NOT LIKE'],
                ],
            ], // allowed cols to filter by
            true // passing data as json (if true) or php array (if false)
        );

        //ddQuery($query);

        try {
            //$query = $query->groupBy('ads.id');
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

            // return success message with data
            return $this->showAny($result);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    // create email and add it to pending list ( status : 0 )
    public function create(Request $req)
    {
        // validate request
        $validator = Validator::make($req->all(), [
            'ad_id' => 'nullable|integer|exists:ads,id',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'string|nullable',
            'message' => 'required|string',
        ]);

        // create email
        $email = new \App\Models\email();

        // set email data
        if ($req->ad_id) {
            $email->ad_id = $req->ad_id;
        }
        $email->name = $req->name;
        $email->email = $req->email;
        $email->phone = $req->phone;
        $email->message = $req->message;

        // save email
        $email->save();

        return $this->showAny($email);
    }

    // create email and add it to pending list ( status : 0 )
    public function createAdsEmail(Request $req)
    {
        // validate request
        $validator = Validator::make($req->all(), [
            'ad_id' => 'required|integer|exists:ads,id',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'string|nullable',
            'message' => 'required|string',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return $this->errorResponse($validator->errors(), 400);
        }

        // check if ad_id exists
        $ad = \App\Models\ads::find($req->ad_id);
        if (!$ad) {
            // return response()->json(['error' => 'Ad not found'], 400);
            return $this->errorResponse('Ads not found', 400);
        }

        // check if ad user is available
        $user = \App\Models\User::find($ad->id_user);
        if (!$user) {
            // return response()->json(['error' => 'Ads user not found'], 400);
            return $this->errorResponse('Ads user not found', 400);
        }

        $email = null;
        if ($ad->email == -1) $email = $user->email;
        else if ($ad->email) {
            $UserContact = UserContact::find($ad->email);
            if ($UserContact) $email = $UserContact->value;
        }

        if (!$email) {
            return $this->errorResponse('Ads user has no email', 400);
        }

        // create email
        $email = new \App\Models\email();

        // set email data
        $email->ad_id = $req->ad_id;
        $email->name = $req->name;
        $email->email = $req->email;
        $email->phone = $req->phone;
        $email->message = $req->message;

        // save email
        $email->save();

        // return success message with data
        // return response()->json([
        //     'status' => 'success',
        //     'data' => $email
        // ]);
        return $this->showAny($email);
    }

    //confirm the email
    public function confirmAdsEmail(Request $req, $id)
    {
        try{
        //check if the id is valid
        if (!is_numeric($id)) {
            // return response()->json(['error' => 'Invalid id'], 400);
            return $this->errorResponse('Invalid id', 400);
        }

        //get the email
        $email = \App\Models\email::find($id);

        //check if email exists
        if (!$email) {
            // return response()->json(['error' => 'Email not found'], 400);
            return $this->errorResponse('Email not found', 400);
        }

        $ad = \App\Models\ads::find($email->ad_id);
        if (!$ad) {
            // return response()->json(['error' => 'Ad not found'], 400);
            return $this->errorResponse('Ads not found', 400);
        }

        // check if ad user is available
        $user = \App\Models\User::find($ad->id_user);
        if (!$user) {
            // return response()->json(['error' => 'Ad user not found'], 400);
            return $this->errorResponse('Ads user not found', 400);
        }

        //check kif user has a valid email
        if (!$user->email) {
            // return response()->json(['error' => 'Ad user has no email'], 400);
            return $this->errorResponse('Ads user has no email', 400);
        }

        //check if email is already confirmed
        if ($email->status == 1) {
            // return response()->json(['error' => 'Email already confirmed'], 400);
            return $this->errorResponse('Email already confirmed', 400);
        }

        $toemail = null;
        if ($ad->email == -1) $toemail = $user->email;
        else if ($ad->email) {
            $UserContact = UserContact::find($ad->email);
            if ($UserContact) $toemail = $UserContact->value;
        }


        //check if user has a valid email
        if (!$toemail) {
            // return response()->json(['error' => 'Ads user has no email'], 400);
            return $this->errorResponse('Ads user has no email', 400);
        }

        // get the usernname
        $username = User::select('username')->where('id', '=', $ad->id_user)->first();

        $adCity = cities::select('name')->where('id', '=', $ad->loccity)->first();


        // get the ad image
        $image = media::select(DB::raw("CONCAT(media.path,media.filename,'.',media.extension) as img"))
            ->join('ad_media', 'ad_media.media_id', '=', 'media.id')
            ->leftjoin('ads', 'ad_media.ad_id', '=', 'ads.id')
            ->where('ads.id', '=', $email->ad_id)
            ->first();

        // Host name
        $host_name = $_SERVER['HTTP_HOST'];

        // in case there is no image
        if (!$image) {
            $img = "/assets/img/no-photo.png";
        } else {
            $img = $image->img;
        }




        // send the email
        Mail::send(new SimpleMail(
            [
                "to" => $toemail,
                "subject" => $email->name . " souhaite vous contacter",
                "view" => "emails.Contact.fr",
                "data" => [
                    "username" => $username['username'],
                    "id" => $email->ad_id,
                    "title" => $ad->title,
                    "price" => $ad->price,
                    "city" => $adCity['name'],
                    "name" => $email->name,
                    "email" => $email->email,
                    "phone" => $email->phone,
                    "content" => $email->message,
                    "image" => $img,
                    "host_name" => $host_name
                ]
            ]
        ));


        //update the email
        $email->status = 1;
        $email->save();







        //return success message
        // return response()->json(['status' => 'success']);
        return $this->showAny(['status' => 'success']);
    } catch (\Throwable $th) {
        DB::rollBack();
        return $this->errorResponse($th->getMessage(), 409);
    }

    }

    public function confirmEmail(Request $req, $id)
    {

        //check if the id is valid
        if (!is_numeric($id)) {
            // return response()->json(['error' => 'Invalid id'], 400);
            return $this->errorResponse('Invalid id', 400);
        }

        //get the email
        $email = \App\Models\email::find($id);

        //check if email exists
        if (!$email) {
            // return response()->json(['error' => 'Email not found'], 400);
            return $this->errorResponse('Email not found', 400);
        }

        //check if email is already confirmed
        if ($email->status == 1) {
            // return response()->json(['error' => 'Email already confirmed'], 400);
            return $this->errorResponse('Email déjà confirmé !', 409);
        }


        // check if ad_id exists
        $ad = \App\Models\ads::find($email->ad_id);
        if (!$ad) {
            // return response()->json(['error' => 'Ad not found'], 400);
            return $this->errorResponse('Ads not found', 400);
        }

        // check if ad user is available
        $user = \App\Models\User::find($ad->id_user);
        if (!$user) {
            // return response()->json(['error' => 'Ads user not found'], 400);
            return $this->errorResponse('Ads user not found', 400);
        }

        $toemail = null;
        if ($ad->email == -1) $toemail = $user->email;
        else if ($ad->email) {
            $UserContact = UserContact::find($ad->email);
            if ($UserContact) $toemail = $UserContact->value;
        }

        //check if user has a valid email
        if (!$toemail) {
            // return response()->json(['error' => 'Ads user has no email'], 400);
            return $this->errorResponse('Ads user has no email', 400);
        }



        //update the email
        $email->status = 1;
        $email->save();

        //return success message
        // return response()->json(['status' => 'success']);
        return $this->showAny(['status' => 'success']);
    }

    // cancel the email
    public function cancelEmail(Request $req, $id)
    {
        // check if the id is valid
        if (!is_numeric($id)) {
            // return response()->json(['error' => 'Invalid id'], 400);
            return $this->errorResponse('Invalid id', 400);
        }

        //get the email
        $email = \App\Models\email::find($id);

        //check if email exists
        if (!$email) {
            // return response()->json(['error' => 'Email not found'], 400);
            return $this->errorResponse('Email not found', 400);
        }

        //check if email is already confirmed
        if ($email->status == 1) {
            // return response()->json(['error' => 'Email already confirmed'], 400);
            return $this->errorResponse('Email déjà confirmé !', 400);
        }

        //update the email
        $email->status = 2;
        $email->save();

        // return success message
        // return response()->json(['status' => 'success']);
        return $this->showAny(['status' => 'success']);
    }

    // Report Abus
    public function reportAbus(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'ad_id' => 'required|integer|exists:ads,id',
                'email' => 'required|email',
                'message' => 'required|string',
                'type' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }

            // check if ad_id exists
            $ad = ads::find($req->ad_id);
            if (!$ad) {
                return $this->errorResponse('Ad not found', 400);
            }

            $user = User::find($ad->id_user);
            if (!$user) {
                return $this->errorResponse('Ads user not found', 400);
            }

            Mail::send(new SimpleMail(
                [
                    "to" => $user->email, // Email for abus
                    "subject" => "Abus détecté !",
                    "view" => "emails.ReportAbus.fr", // Need a view to be created
                    "data" => [
                        "email" => $req->email,
                        "type" => $req->type,
                        'id' => $ad->id,
                        'title' => $ad->title,
                        'messages' => $req->message
                    ]
                ]
            ));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    // public function updatePasswordUsers(){

    //      try{
    //         $users = DB::table('users')
    //         ->where('email','tijani.idrissi.abdellatif@gmail.com')  // find your user by their email  // optional - to ensure only one record is updated.
    //         ->update(array('password' => '$2a$12$g.mZpj3MEYLk1dzCAZxv9uS/iSHA1UO.avuGtuz4D.WIQdXfcELQS'));

    //         return $this->showAny('success',200);
    //      }catch(\Throwable $th){

    //           return $this->errorResponse($th->getMessage(),500);
    //      }

    // }

    public function getUserByEmail(Request $request){

        $validator = Validator::make($request->all(),[

          'email' => 'required|email'
        ]);

        if($validator->fails()){

           return $this->errorResponse('Email does not exist',404);
        }

        $user = DB::table('users')
        ->select('email','username','firstname','lastname')
        ->where('id',$request->email)
        ->get();



        return $this->showAny($user,200);


  }

    public function getUserEmail(Request $request){

          $validator = Validator::make($request->all(),[

            'id' => 'integer'
          ]);

          if($validator->fails()){

             return $this->errorResponse('Email does not exist',404);
          }

          $user = DB::table('users')
          ->select('email','username','firstname','lastname')
          ->where('id',$request->id)
          ->get();



          return $this->showAny($user,200);


    }




    public function sendWelcomeEmail(){

        try{

            $host_name = $_SERVER['HTTP_HOST'];

           $raw = DB::table('users')
           ->select('username','email')
           ->where('usertype',4)
           ->orderBy('id')
           ->get()
           ->toArray();

           $slice_2 = array_slice($raw,100,100);
           array_push($slice_2,(object)[

            'email' => 'abdellativovich@hotmail.com',
            "username" => 'abdellatif tijani'
           ]);


         $slice_adempted = array_slice($slice_2,27,100);
            return $this->showAny($slice_adempted ,200);

        }catch(\Throwable $th){

            return $this->errorResponse($th->getMessage(),500);
        }

    }


}
