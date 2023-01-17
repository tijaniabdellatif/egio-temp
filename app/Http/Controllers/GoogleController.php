<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Lib\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\SimpleMail;
use Illuminate\Support\Facades\Mail;


class GoogleController extends Controller
{

    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {

        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->user();
            $finduser = User::where('email', $user->email)->first();

        
            if ($finduser) {

                setcookie('isGoogleAuth', true, null, "/");

                $token = $finduser->createToken('MyApp')->plainTextToken;
                session(['token' => $token]);
                session(['auth' => $finduser]);
                setcookie('jwt', Crypt::encryptString($token), null, "/");
                $user = json_encode($finduser);
                setcookie('user', Crypt::encryptString($user), time() + (86400 * 30), "/");

                return redirect('dashboard');
            } else {

                function rand_string($length)
                {

                    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    return substr(str_shuffle($chars), 0, $length);
                }

                $randomPwd = rand_string(8);

                $newUser = User::create([
                    'username' => str_replace(' ', '', $user->name . "-GL"),
                    'email' => $user->email,
                    'password' => bcrypt($randomPwd),
                    'userType' => 5,
                    'authtype' => 2,
                    'firstname' => $user->user['family_name'],
                    'lastname' => $user->user['given_name'],
                    'phone' => "",
                ]);
                if ($newUser) {


                    try {

                        $host_name = $_SERVER['HTTP_HOST'];

                        Mail::send(new SimpleMail(
                            [
                                "to" => $newUser->email,
                                "subject" => "Bienvenue " . $newUser->firstname . " Parmi nous",
                                "view" => "emails.UserGoogle.fr",
                                "data" => [
                                    "password" => $randomPwd,
                                    "email" => $newUser->email,
                                    "username" => str_replace(' ', '', $newUser->username),
                                    "name" => $newUser->firstname,
                                    "host_name" => $host_name
                                ]
                            ]
                        ));

                        Auth::login($newUser);
                        setcookie('NewUserFromGoogle', true, null, "/");

                        $token =  $newUser->createToken('MyApp')->plainTextToken;
                        $newUser = json_encode($newUser);

                        session(['token' => $token]);
                        session(['auth' => $newUser]);


                        setcookie('jwt', Crypt::encryptString($token), null, "/");
                        setcookie('user', Crypt::encryptString($newUser), time() + (86400 * 30), "/");

                        return redirect()->intended('dashboard');

                        // return redirect('dashboard');

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