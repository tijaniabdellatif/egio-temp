<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\SimpleMail;
use Illuminate\Support\Facades\Mail;

class facebookController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }


    // Callback du provider
    public function callback()
    {


        try {

            $user = Socialite::driver('facebook')->user();
            $finduser = User::where('email', $user->email)->first();

            if ($finduser) {

                setcookie('isFacebookAuth', true, null, "/");

                $token = $finduser->createToken('MyApp')->plainTextToken;
                $user = json_encode($finduser);

                session(['token' => $token]);
                session(['auth' => $user]);

                setcookie('jwt', Crypt::encryptString($token), null, "/");

                // parse $user to json
                // add another key value pair to the cookies
                setcookie('user', Crypt::encryptString($user), time() + (86400 * 30), "/");

                return redirect("dashboard");
            } else {


                // Generating random password
                function rand_string($length)
                {

                    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    return substr(str_shuffle($chars), 0, $length);
                }

                $randomPwd = rand_string(8);

                $newUser = User::create([
                    'username' => str_replace(' ', '', $user->name . "-FB"),
                    'email' => $user->email,
                    // 'google_id'=> $user->id,
                    'password' => bcrypt($randomPwd),
                    'userType' => 5,
                    'authtype' => 2,
                    'firstname' => $user->name,
                    'lastname' => " ",
                    'phone' => null,
                ]);

                if ($newUser) {

                    Auth::login($newUser);

                    setcookie('NewUserFromFacebook', true, null, "/");

                    $token =  $newUser->createToken('MyApp')->plainTextToken;
                    setcookie('jwt', Crypt::encryptString($token), null, "/");
                    $user = json_encode($newUser);

                    session(['token' => $token]);
                    session(['auth' => $user]);

                    setcookie('user', Crypt::encryptString($user), time() + (86400 * 30), "/");

                    try {

                        $host_name = $_SERVER['HTTP_HOST'];

                        Mail::send(new SimpleMail(
                            [
                                "to" => $newUser->email,
                                "subject" => "Bienvenue " . $newUser->firstname . " Parmi nous",
                                "view" => "emails.UserFacebook.fr",
                                "data" => [
                                    "password" => $randomPwd,
                                    "email" => $newUser->email,
                                    "username" => str_replace(' ', '', $newUser->username . "-FB"),
                                    "name" => $newUser->firstname,
                                    "host_name" => $host_name
                                ]
                            ]
                        ));

                        return redirect('dashboard');
                    } catch (Exception $ex) {
                        return $ex->getMessage();
                        // return "We've got errors!";
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('login')->with('error', $e->getMessage());
        }
    }
}