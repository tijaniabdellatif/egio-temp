<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\SimpleMail;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalAccessToken;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\AdsUserCheck;
use App\Models\pro_user_info;

class authController extends ApiController
{

    const VALID_INTEGER = 'required|integer';

    protected $usersTypes = [4, 3 , 5];

    public function register(Request $request)
    {
        try {
            $validatorArr = [
                'email' => 'required|email:rfc,dns|unique:users',
                'password' => 'required|min:8',
                "password2" => "required|same:password",
                'userType' => 'required',
                'firstname' => 'required',
                'lastname' => 'required',
                'phone' => 'required|phone:AUTO,MA,FR,BE,UK',
                'username' => 'required|unique:users',
                'birthdate' => 'date|nullable'
            ];

            if ($request->userType != 5) {
                $validatorArr['companyName'] = 'required|string|max:255';
            }

            $validator = Validator::make($request->all(), $validatorArr);


            // if not valid return error
            if ($validator->fails()) {

                return $this->errorResponse($validator->errors(), 400);
            }


            if(!in_array($request->userType, $this->usersTypes)){
                return $this->errorResponse('User type invalid', 400);
            }

            DB::beginTransaction();

            $user = new User();
            $user->password = bcrypt($request->password);
            $user->firstname = $request->firstname; #TODO: getters and setters to lowercase the columns
            $user->lastname = $request->lastname;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->birthdate = $request->birthdate;

            // set the user type to 5 ( particulier )
            $user->usertype = $request->userType;

            //if $request->authtype is exist
            if (isset($request->authtype)) {
                $user->authtype = $request->authtype;
            } else {
                $user->authtype = 1;
            }


            $state = $user->save();



            if (!$state) {
                DB::rollBack();
                return $this->errorResponse('Error while registering user', 500);
            }

            if ($request->userType != 5) {
                $prouser = new pro_user_info();
                $prouser->user_id = $user->id;
                $prouser->company = $request->companyName;
                $state1 = $prouser->save();
                if (!$state1) {
                    DB::rollBack();
                    return $this->errorResponse('Error while registering user', 500);
                }
            }

            DB::commit();

            $email = $user->email;
            $data = [
                'name' => $request->get('firstname') . "  " . $request->get('lastname'),
                'email' => $request->get('email'),
                'username' => $request->get('username'),
                'phone' => $request->get('phone'),
            ];

            $host_name = $_SERVER['HTTP_HOST'];

            Mail::send(new SimpleMail(
                [
                    "to" => $email,
                    "subject" => "Bienvenue " . $data['name'] . " Parmi nous",
                    "view" => "emails.UserRegistred.fr",
                    "data" => [
                        "name" => $data["name"],
                        "email" => $data["email"],
                        "username" => $data["username"],
                        "host_name" => $host_name
                    ]
                ]
            ));

            // return response()->json($user);
            $this->showAny($user);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function registerAndLogin(Request $request)
    {
        //api requst validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            "password2" => "required|same:password",
            'firstname' => 'required',
            'lastname' => 'required',
            'phone' => 'required',
            'username' => 'required|unique:users',
        ]);

        $adValidator = Validator::make($request->all(), [

            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric',
            'loccity' => self::VALID_INTEGER,
            'locdept' => self::VALID_INTEGER
        ]);

        $raw = [];

        if ($validator->fails() || $adValidator->fails()) {

            $raw =   array_merge(
                $validator->errors()->toArray(),
                (array) $adValidator->errors()->toArray()
            );
            return $this->errorResponse((object) $raw, 422);
        }

        $user = new User();
        $user->password = bcrypt($request->password);
        $user->firstname = $request->firstname; #TODO: getters and setters to lowercase the columns
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // set the user type to 5 ( particulier )
        $user->usertype = 5;

        //if $request->authtype is exist
        if (isset($request->authtype)) {
            $user->authtype = $request->authtype;
        } else {
            $user->authtype = 1;
        }



        $state = $user->save();

        if (!$state) {
            return $this->errorResponse('Error while registering user', 500);
        }

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        //$success['auth'] =  ['id'=>$user->id,'username'=>$user->username,'firstname'=>$user->firstname,'lastname'=>$user->lastname,'email'=>$user->email];
        $success['auth'] =  $user; //i need all selected user columns data

        setcookie('jwt', Crypt::encryptString($success['token']), null, "/");

        // dd(Cookie::get('jwt'));
        // parse $user to json
        $user = json_encode($user);
        // add another key value pair to the cookies
        setcookie('user', Crypt::encryptString($user), null, "/");

        // return response()->json(['success' => $success], 200)->withCookie($cookie);
        return $this->showAny($success);
    }

    public function loginS(Request $request)
    {
        // login user [email,password] and return it as json, with validation

        //validate request

        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        // decript the password and compare it with the current user
        $user = User::select('users.email', DB::raw("CONCAT(media.path,media.filename,'.',media.extension) AS avatar"), 'users.username', 'users.firstname', 'users.lastname', 'users.password', 'users.id', 'users.usertype', 'users.status')
            ->leftJoin('user_info', 'user_info.user_id', '=', 'users.id')
            ->leftJoin('media', 'user_info.avatar', '=', 'media.id')
            ->where('email', $request->email)
            // the user can be logged in with the username ( we will check if the email is holding a valid username, it's bulshit! Haha :D )
            ->orWhere('username', $request->email);


        // with usersTypes(id,designation) & UserInfo *
        $user->with(['usersTypes', 'UserInfo']);

        $user = $user->first(['email', 'username', 'firstname', 'lastname', 'password', 'id', 'status', 'usertype', 'avatar']);


        if (!$user) {
            return $this->errorResponse('Utilisateur introuvable', 404);
        }
        // if (!Hash::check($request->password, $user->password)) {
        //     return $this->errorResponse('Le mot de passe est incorrect', 401);
        // }

        if ($user['status'] == '70') {
            return $this->errorResponse('Votre compte est inactif', 409);
        }

        if ($user['status'] == '60') {
            return $this->errorResponse('Votre compte est expiré', 409);
        }


        // if not valid return error
        if ($validator->fails()) {
            //retur validation error with error messages
            // return response()->json(['error'=>$validator->errors()], 401);
            return $this->errorResponse($validator->errors(), 401);
        }


        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        //$success['auth'] =  ['id'=>$user->id,'username'=>$user->username,'firstname'=>$user->firstname,'lastname'=>$user->lastname,'email'=>$user->email];
        $success['auth'] =  $user; //i need all selected user columns data

        setcookie('jwt', Crypt::encryptString($success['token']), null, "/");

        // dd(Cookie::get('jwt'));
        // parse $user to json
        $user = json_encode($user);


        // add another key value pair to the cookies
        setcookie('user', Crypt::encryptString($user), time() + (86400 * 30), "/");

        // return response()->json(['success' => $success], 200)->withCookie($cookie);
        return $this->showAny($success);
    }

    public function login(Request $request)
    {
        // login user [email,password] and return it as json, with validation

        //validate request

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        // decript the password and compare it with the current user
        $user = User::select('users.email', DB::raw("CONCAT(media.path,media.filename,'.',media.extension) AS avatar"), 'users.username', 'users.firstname', 'users.lastname', 'users.password', 'users.id', 'users.usertype', 'users.status')
            ->leftJoin('user_info', 'user_info.user_id', '=', 'users.id')
            ->leftJoin('media', 'user_info.avatar', '=', 'media.id')
            ->where('email', $request->email)
            // the user can be logged in with the username ( we will check if the email is holding a valid username, it's bulshit! Haha :D )
            ->orWhere('username', $request->email);


        // with usersTypes(id,designation) & UserInfo *
        $user->with(['usersTypes', 'UserInfo']);

        $user = $user->first(['email', 'username', 'firstname', 'lastname', 'password', 'id', 'status', 'usertype', 'avatar']);


        if (!$user) {
            return $this->errorResponse('Utilisateur introuvable', 404);
        }
        if (!Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Le mot de passe est incorrect', 401);
        }

        if ($user['status'] == '70') {
            return $this->errorResponse('Votre compte est inactif', 409);
        }

        if ($user['status'] == '60') {
            return $this->errorResponse('Votre compte est expiré', 409);
        }




        // hide user password
        unset($user->password);

        // if not valid return error
        if ($validator->fails()) {
            //retur validation error with error messages
            // return response()->json(['error'=>$validator->errors()], 401);
            return $this->errorResponse($validator->errors(), 401);
        }


        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        //$success['auth'] =  ['id'=>$user->id,'username'=>$user->username,'firstname'=>$user->firstname,'lastname'=>$user->lastname,'email'=>$user->email];
        $success['auth'] =  $user; //i need all selected user columns data

        setcookie('jwt', Crypt::encryptString($success['token']), null, "/");

        // dd(Cookie::get('jwt'));
        // parse $user to json
        $user = json_encode($user);


        // add another key value pair to the cookies
        setcookie('user', Crypt::encryptString($user), time() + (86400 * 30), "/");

        // return response()->json(['success' => $success], 200)->withCookie($cookie);
        return $this->showAny($success);
    }

    public function logout(Request $request)
    {

        if (method_exists($request->user()->currentAccessToken(), 'delete')) {

            Cookie::forget('jwt');
            Cookie::forget('user');
            $request->user()->currentAccessToken()->delete();

            return $this->showAny('Successfully logged out');
        } else {

            Cookie::queue(Cookie::forget('user'));
            Cookie::queue(Cookie::forget('jwt'));
            auth()->guard('web')->logout();
        }




        // return response()->json('Successfully logged out');

    }

    public function loginForTest(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'usertype' => 'required|integer'
        ]);

        // if not valid return error
        if ($validator->fails()) {
            //retur validation error with error messages
            // return response()->json(['error'=>$validator->errors()], 401);
            return $this->errorResponse($validator->errors(), 401);
        }

        // get random user by usertype
        $user = User::select('email', 'username', 'firstname', 'lastname', 'id')->where('usertype', $request->usertype)->inRandomOrder()
            ->with([
                'UserInfo' => function ($query) {
                    $query->select('id', 'gender');
                },
                'usersTypes' => function ($query) {
                    $query->select('id', 'designation');
                }
            ])->first();



        // if no user found return error
        if (!$user) {
            // return response()->json(['error'=>'No user found'], 404);
            return $this->errorResponse('No user found', 404);
        }

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['auth'] =  $user->hide(['id', 'password', 'remember_token', 'usertype']);

        // $cookie = cookie('jwt', $success['token'], 3600);

        $cookie = cookie('jwt', $success['token'], 3600);

        // parse $user to json
        $user = json_encode($user);
        // add another key value pair to the cookies
        $cookie2 = cookie('auth', $user, 3600);

        // return response()->json(['success' => $success], 200)->withCookie($cookie);
        return $this->showAny($success)->withCookie($cookie)->withCookie($cookie2);
    }

    // request reset password
    public function requestResetPassword(Request $request)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        // if not valid return error
        if ($validator->fails()) {
            //retur validation error with error messages
            return $this->errorResponse($validator->errors(), 401);
        }

        // check if email is valid
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            // get user by id
            $user = User::where('id', $request->email)->first();

            // if no user found return error
            if (!$user) {
                return $this->errorResponse('Utilisateur introuvable', 404);
            }

            // change the id in the request to the email
            $request->email = $user->email;
        }

        // get user by email
        $user = User::where('email', $request->email)->first();
        // if no user found return error
        if (!$user) {
            return $this->errorResponse('No user found', 404);
        }

        // generate random 4 digit code
        $code = rand(1000, 9999);

        $host_name = $_SERVER['HTTP_HOST'];

        //send email to user with code
        Mail::send(new SimpleMail(
            [
                "to" => $user->email,
                "subject" => "Multilist | Rénitialisation du mot de passe",
                "view" => "emails.ResetPassword.fr",
                "data" => [
                    "username" => $user->username,
                    "title" => "Multilist | Rénitialisation du mot de passe",
                    "code" => $code,
                    "host_name" => $host_name,
                    "id" => $user->id,
                ]
            ]
        ));

        // save code to user
        $passwordReset = PasswordReset::where('email', $user->email)->first();

        if (!$passwordReset) {
            $passwordReset = new PasswordReset();
            $passwordReset->email = $user->email;
        }

        $passwordReset->code = $code;
        $passwordReset->expired_at = Carbon::now()->addMinutes(10);
        $passwordReset->save();

        return $this->showAny('Email sent successfully');
    }

    // check if code is valid
    public function verifycode(Request $request)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'email' => 'required'
        ]);

        // if not valid return error
        if ($validator->fails()) {
            //retur validation error with error messages
            return $this->errorResponse($validator->errors(), 401);
        }

        // check if email is valid
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            // get user by id
            $user = User::where('id', $request->email)->first();

            // if no user found return error
            if (!$user) {
                return $this->errorResponse('No user found', 404);
            }

            // change the id in the request to the email
            $request->email = $user->email;
        }

        // check if user is valid
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->errorResponse('No user found', 404);
        }

        // make sur that code is 4 digits, if not change it to 4 digits
        $code = str_pad($request->code, 4, '0', STR_PAD_LEFT);


        // check if code is valid
        $passwordReset = PasswordReset::where('code', $code)
            ->where('email', $request->email)
            ->first();

        // if no code found return error
        if (!$passwordReset) {
            return $this->errorResponse('Code is invalid', 404);
        }

        $expired_at = Carbon::parse($passwordReset->expired_at);

        if ($expired_at < Carbon::now()) {
            // delete code from database
            $passwordReset->delete();
            return $this->errorResponse('Code expired', 401);
        }

        return $this->showAny('Code is valid');
    }

    // reset password
    public function resetPassword(Request $request)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'code' => 'required|integer',
            'password' => 'required|min:6|confirmed'
        ]);

        // check if email is valid
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            // get user by id
            $user = User::where('id', $request->email)->first();

            // if no user found return error
            if (!$user) {
                return $this->errorResponse('No user found', 404);
            }

            // change the id in the request to the email
            $request->email = $user->email;
        }

        // if not valid return error
        if ($validator->fails()) {
            // return validation error with error messages
            return $this->errorResponse($validator->errors(), 401);
        }

        // get user by email
        $user = User::where('email', $request->email)
            ->first();

        // if no user found return error
        if (!$user) {
            return $this->errorResponse('No user found', 404);
        }

        // get code from user
        $passwordReset = PasswordReset::where('email', $request->email)->first();

        // if no code found return error
        if (!$passwordReset) {
            return $this->errorResponse('No code found', 404);
        }

        // get expired_at and check if code is expired
        $expired_at = Carbon::parse($passwordReset->expired_at);

        if ($expired_at < Carbon::now()) {
            // delete code from database
            $passwordReset->delete();
            return $this->errorResponse('Code expired', 401);
        }

        $code = $passwordReset->code;

        // if code is not equal to the code in the request return error
        if ($passwordReset->code != $request->code) {
            return $this->errorResponse('Code is not equal to the code in the request', 401);
        }

        // update user password
        $user->password = Hash::make($request->password);
        $user->save();

        // delete code from database
        $passwordReset->delete();

        return $this->showAny('Password updated successfully');
    }

    // check if token is valid and return user
    public function checkToken(Request $request)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'token' => 'required|string'
        ]);

        // if not valid return error
        if ($validator->fails()) {
            // return validation error with error messages
            return $this->errorResponse($validator->errors(), 401);
        }

        // get user by token
        $user = User::where('token', $request->token)->first();

        // if no user found return error
        if (!$user) {
            return $this->errorResponse('No user found', 404);
        }

        // return user
        return $this->showAny($user);
    }
}
