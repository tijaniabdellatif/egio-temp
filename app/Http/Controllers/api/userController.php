<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\user_type;
use App\Mail\SimpleMail;
use App\Models\UserContact;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;
use App\Notifications\AnnounceNotifications;
use Illuminate\Support\Facades\Notification;

class userController extends ApiController
{

    protected $proUsersTypes = [4, 3];

    public function __construct()
    {
        $this->middleware('can:create,user');
        $this->middleware('can:update,user');
    }

    // filter query
    public function filter(Request $req)
    {


        /*$check = auth()->user()->usertype;



        if ($check === 6) {

            $query = \App\Models\User::select('users.id', 'users.firstname', 'users.lastname', DB::raw('CONCAT(users.firstname," ",users.lastname) as fullname'), DB::raw('COUNT(ads.id) as nbr_ads'), 'auth_type.designation as auth_type_designation', 'user_type.designation as user_type_designation', 'users.username', 'users.email', 'pro_user_info.company', 'users.phone', 'users.usertype', 'users.birthdate', 'users.created_at', 'users.updated_at', 'users.expiredate', 'users.authtype', 'users.assigned_ced', 'users.status', 'users.coins')
                ->where('users.email','NOT LIKE','%multilist.ma')
                ->leftJoin('user_type', 'users.usertype', '=', 'user_type.id')
                ->where('users.usertype', '!=', 1)
                ->Where('users.usertype', '!=', 2)
                ->Where('users.usertype', '!=', 6)
                ->Where('users.usertype', '!=', 7)
                ->Where('users.usertype', '!=', 8)
                ->Where('users.usertype', '!=', 9)
                ->leftJoin('auth_type', 'users.authtype', '=', 'auth_type.id')
                ->leftJoin('pro_user_info', 'users.id', '=', 'pro_user_info.user_id')
                ->leftJoin('ads', 'users.id', '=', 'ads.id_user')
                ->groupBy('users.id')
                ->orderBy('users.id', 'desc');


            // the filter helper function
            $query = queryFilter(
                $req->where, // json data
                $query,
                [
                    // 'user_info' => [
                    //     'type' => 'leftJoin',
                    //     'foreign_key' => 'users.id',
                    //     'primary_key' => 'user_info.id_user',
                    //     'op' => '='
                    // ]
                ], // joins
                [
                    'id' => [
                        'type' => 'int',
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'firstname' => [
                        'type' => 'string',
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'lastname' => [
                        'type' => 'string',
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'users.email' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'username' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'users.phone' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'usertype' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'birthdate' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'assigned_ced' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'created_at' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'updated_at' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'expiredate' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'authtype' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'users.status' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'coins' => [
                        'operators' => ['<=', '>=', '<', '>', '=', '!=', 'LIKE', 'NOT LIKE'],
                    ]
                ], // allowed cols to filter by
                true // passing data as json (if true) or php array (if false)
            );

            try {
                $result = [];
                //check if query has sort and order
                if (isset($req->sort) && isset($req->order)) {
                    $query = $query->orderBy($req->sort, $req->order);
                }

                // limit
                if (isset($req->limit)) {
                    $query = $query->take($req->limit);
                }

                //check if request has per_page parameter
                if ($req->has('per_page')) {
                    // get the data
                    $result = $query->paginate($req->per_page);
                } else {
                    // get the data
                    $result = $query->get();
                }

                return $this->showAny($result);
            } catch (\Exception $e) {
                // return response()->json(['error' => 'Check your columns or tables names'], 400);
                return $this->errorResponse($e->getMessage(), 400);
            }
        } else {*/


            // build query using $data
            $query = \App\Models\User::select('users.id', 'users.firstname', 'users.lastname', DB::raw('CONCAT(users.firstname," ",users.lastname) as fullname'), DB::raw('COUNT(ads.id) as nbr_ads'), 'auth_type.designation as auth_type_designation', 'user_type.designation as user_type_designation', 'users.username', 'users.email', 'pro_user_info.company', 'users.phone', 'users.usertype', 'users.birthdate', 'users.created_at', 'users.updated_at', 'users.expiredate', 'users.authtype', 'users.assigned_ced', 'users.status', 'users.coins')

                 ->leftJoin('user_type', 'users.usertype', '=', 'user_type.id')
                ->leftJoin('auth_type', 'users.authtype', '=', 'auth_type.id')
                ->leftJoin('pro_user_info', 'users.id', '=', 'pro_user_info.user_id')
                ->leftJoin('ads', 'users.id', '=', 'ads.id_user')
                //->where('users.email','NOT LIKE','%multilist.ma')
                ->groupBy('users.id')
                ->orderBy('users.id', 'desc');





            // the filter helper function
            $query = queryFilter(
                $req->where, // json data
                $query,
                [
                    // 'user_info' => [
                    //     'type' => 'leftJoin',
                    //     'foreign_key' => 'users.id',
                    //     'primary_key' => 'user_info.id_user',
                    //     'op' => '='
                    // ]
                ], // joins
                [
                    'users.id' => [
                        'type' => 'int',
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'firstname' => [
                        'type' => 'string',
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'lastname' => [
                        'type' => 'string',
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'users.email' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'username' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'users.phone' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'usertype' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'birthdate' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'assigned_ced' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'created_at' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'updated_at' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'expiredate' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'authtype' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'users.status' => [
                        'operators' => ['=', '!=', 'LIKE', 'NOT LIKE'],
                    ],
                    'coins' => [
                        'operators' => ['<=', '>=', '<', '>', '=', '!=', 'LIKE', 'NOT LIKE'],
                    ]
                ], // allowed cols to filter by
                true // passing data as json (if true) or php array (if false)
            );

            try {
                //dd($query->toSql());
                $result = [];
                //check if query has sort and order
                if (isset($req->sort) && isset($req->order)) {
                    $query = $query->orderBy($req->sort, $req->order);
                }

                // limit
                if (isset($req->limit)) {
                    $query = $query->take($req->limit);
                }

                //check if request has per_page parameter
                if ($req->has('per_page')) {
                    // get the data
                    $result = $query->paginate($req->per_page);
                } else {
                    // get the data
                    $result = $query->get();
                }

                return $this->showAny($result);
            } catch (\Exception $e) {
                // return response()->json(['error' => 'Check your columns or tables names'], 400);
                return $this->errorResponse($e->getMessage(), 400);
            }
        //}
    }

    // get all users types
    public function getUsersTypes(Request $req)
    {
        try {
            $result = user_type::get();



            return $this->showAny($result);
        } catch (\Exception $e) {
            // return response()->json(['error' => 'Somthing wrong!'], 400);
            return $this->errorResponse("Something wrong", 400);
        }
    }

    public function createUser(Request $req)
    {
        // validate request data
        $validatorArr = [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255|unique:users|phone:AUTO,MA,FR,BE,UK,NL,US',
            'birthdate' => 'date|nullable',
            'password' => 'required|string|min:8',
            'password2' => 'required|string|min:8|same:password',
            'assigned_ced' => 'integer|nullable',
            'user_type_id' => 'required|integer',
            'user_image_id' => 'integer|nullable',
            'gender' => 'nullable|string',
            'bio' => 'string|nullable',
            'phones' => 'array|nullable',
            'phones.*.value' => 'string',
            'emails' => 'array|nullable',
            'emails.*.value' => 'email',
            'whatsapp' => 'array|nullable',
            'whatsapp.*.value' => 'string'
        ];
        if (in_array($req->user_type_id, $this->proUsersTypes)) {
            $validatorArr['company_name'] = 'required|string|max:255';
            $validatorArr['company_city'] = 'nullable|integer|max:255';
            $validatorArr['company_address'] = 'nullable|string|max:255';
            $validatorArr['company_website'] = 'nullable|string|max:255';
            $validatorArr['company_image_id'] = 'integer|nullable';
            $validatorArr['company_banner_id'] = 'integer|nullable';
            $validatorArr['company_video_id'] = 'integer|nullable';
            $validatorArr['company_videoembed'] = 'integer|nullable';
            $validatorArr['company_audio_id'] = 'integer|nullable';
        }
        // validate request data
        $validator = Validator::make($req->all(), $validatorArr);
        // check if validation fails
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }
        DB::beginTransaction();
        // create new user
        $user = new \App\Models\user();
        $user->firstname = $req->firstname;
        $user->lastname = $req->lastname;
        $user->username = $req->username;
        $user->email = $req->email;
        $user->phone = $req->phone;
        $user->password = Hash::make($req->password);
        $user->assigned_ced = $req->assigned_ced;
        $user->usertype = $req->user_type_id;
        $user->birthdate = $req->birthdate;
        $user->status = "10";
        $check = $user->save();
        // check if user is created
        if (!$check) {
            DB::rollBack();
            return $this->errorResponse("Something wrong", 400);
        } else {
            if ($req->permissions) {
                $permissions = array($req->permissions);
                $user->givePermissionTo($permissions);
            };
            if ($req->role) {
                $role = $req->role;
                $user->assignRole($role);
            };
        };
        // create user info
        $userInfo = new \App\Models\user_info();
        $userInfo->user_id = $user->id;
        $userInfo->avatar = $req->user_image_id;
        $userInfo->bio = $req->bio;
        $userInfo->gender = $req->gender;
        $check = $userInfo->save();
        // check if user info is created
        if (!$check) {
            DB::rollBack();
            return $this->errorResponse("Something wrong", 400);
        }
        // add contacts to user info
        if ($req->phones) {
            foreach ($req->phones as $phone) {
                $contact = new \App\Models\UserContact();
                $contact->user_id = $user->id;
                $contact->type = 'phone';
                $contact->value = $phone['value'];
                $check = $contact->save();
                if (!$check) {
                    DB::rollBack();
                    return $this->errorResponse("Somthing wrong", 400);
                }
            }
        }
        if ($req->emails) {
            foreach ($req->emails as $email) {
                $contact = new \App\Models\UserContact();
                $contact->user_id = $user->id;
                $contact->type = 'email';
                $contact->value = $email['value'];
                $check = $contact->save();
                if (!$check) {
                    DB::rollBack();
                    return $this->errorResponse("Somthing wrong", 400);
                }
            }
        }
        if ($req->whatsapp) {
            foreach ($req->whatsapp as $whatsapp) {
                $contact = new \App\Models\UserContact();
                $contact->user_id = $user->id;
                $contact->type = 'whatsapp';
                $contact->value = $whatsapp['value'];
                $check = $contact->save();
                if (!$check) {
                    DB::rollBack();
                    return $this->errorResponse("Somthing wrong", 400);
                }
            }
        }
        // check if user type is agency
        if (in_array($req->user_type_id, $this->proUsersTypes)) {
            // create user company
            $userCompany = new \App\Models\pro_user_info();
            $userCompany->user_id = $user->id;
            $userCompany->company = $req->company_name;
            $userCompany->city = $req->company_city;
            $userCompany->address = $req->company_address;
            $userCompany->website = $req->company_website;
            $userCompany->image = $req->company_image_id;
            $userCompany->video = $req->company_video_id;
            $userCompany->videoembed = $req->company_videoembed;
            $userCompany->audio = $req->company_audio_id;
            $userCompany->probannerimg = $req->company_banner_id;
            $check = $userCompany->save();
            // check if user company is created
            if (!$check) {
                DB::rollBack();
                return $this->errorResponse("Somthing wrong", 400);
            }
        }
        DB::commit();
        (new LogActivity())->addToLog($user->id, $req);
        return $this->showAny("User created successfully", 200);
    }

    public function updateUser(Request $req, $id)
    {

        // add user id to request
        $req->request->add(['id' => $id]);

        $validatorArr = [
            'id' => 'required|integer',
            'firstname' => 'string|nullable',
            'lastname' => 'string|nullable',
            'username' => 'string|unique:users,username,' . $req->id . '|nullable',
            'email' => 'string|unique:users,email,' . $req->id . '|nullable',
            'phone' => 'string|phone:AUTO,MA,FR,BE,UK,NL,US' . $req->id . '|nullable',
            'birthdate' => 'date|nullable',
            'assigned_ced' => 'integer|nullable',
            'usertype' => 'integer|nullable',
            'user_image_id' => 'integer|nullable',
            'bio' => 'string|nullable',
            'gender' => 'string|nullable',
            'phones' => 'array|nullable',
            'phones.*.value' => 'string',
            'emails' => 'array|nullable',
            'emails.*.value' => 'email',
            'whatsapp' => 'array|nullable',
            'whatsapp.*.value' => 'string'
        ];


        // validation for pro user
        if (in_array($req->usertype, $this->proUsersTypes)) {

            // add to validatorArr
            $validatorArr = array_merge($validatorArr, [
                'address' => 'string|nullable',
                'city' => 'integer|nullable',
                'company' => 'string|nullable',
                'website' => 'string|nullable',
                'company_image_id' => 'integer|nullable',
                'company_banner_id' => 'integer|nullable',
                'company_video_id' => 'integer|nullable',
                'company_videoembed' => 'integer|nullable',
                'company_audio_id' => 'integer|nullable',
            ]);
        }

        // find the user
        $user = \App\Models\User::find($req->id);

        // if not exist
        if (!$user) {
            return $this->errorResponse('User not found', 404);
        } else {
            if ($req->permissions) {
                $permissions = array($req->permissions);
                $user->givePermissionTo($permissions);
            }

            if ($req->role) {
                $role = $req->role;
                $user->assignRole($role);
            }
        }

        $user_info = \App\Models\user_info::where('user_id', $req->id)->first();

        if (!$user_info) {
            $validatorArr = array_merge($validatorArr, [
                'user_image_id' => 'integer|nullable',
                'bio' => 'string|nullable',
                'gender' => 'string|nullable'
            ]);

            $user_info = new \App\Models\user_info();
        }

        $validator = Validator::make($req->all(), $validatorArr);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        // start transaction
        DB::beginTransaction();

        try {

            // update the user
            $user->firstname = $req->firstname ?? $user->firstname;
            $user->lastname = $req->lastname ?? $user->lastname;
            $user->username = $req->username ?? $user->username;
            $user->email = $req->email ?? $user->email;
            $user->phone = $req->phone ?? $user->phone;
            $user->assigned_ced = $req->assigned_ced ?? $user->assigned_ced;
            $user->birthdate = $req->birthdate ?? $user->birthdate;
            $user->usertype = $req->usertype ?? $user->usertype;
            /*if (!$user->isDirty()) {

                return $this->errorResponse('Rien à modifier', 409);
            }*/
            $user->save();

            // update user_info
            // get user_info if not exist create new one if exist update it

            $user_info->user_id = $req->id ?? $user_info->user_id;
            $user_info->bio = $req->bio ?? $user_info->bio;
            $user_info->gender = $req->gender ?? $user_info->gender;
            $user_info->avatar = $req->user_image_id ?? $user_info->avatar;
            $user_info->save();

            // update contacts
            // delete all user contacts and add the new ones

            $contact_ids = [];

            // get all $req->phones ids
            if ($req->phones) {
                foreach ($req->phones as $phone) {
                    if (isset($phone['id'])) {
                        $contact_ids[] = $phone['id'];
                    }
                }
            }

            // get all $req->emails ids
            if ($req->emails) {
                foreach ($req->emails as $email) {
                    if (isset($email['id'])) {
                        $contact_ids[] = $email['id'];
                    }
                }
            }

            // get all $req->whatsapp ids
            if ($req->whatsapp) {
                foreach ($req->whatsapp as $whatsapp) {
                    if (isset($whatsapp['id'])) {
                        $contact_ids[] = $whatsapp['id'];
                    }
                }
            }

            // delete all contacts who are not in $contact_ids
            UserContact::where('user_id', $req->id)->whereNotIn('id', $contact_ids)->delete();

            if ($req->phones) {
                foreach ($req->phones as $key => $phone) {
                    $user_contact = new UserContact();

                    if (isset($phone['id'])) {
                        $user_contact = UserContact::find($phone['id']);
                    }

                    $user_contact->user_id = $req->id;
                    $user_contact->type = 'phone';
                    $user_contact->value = $phone['value'];
                    $user_contact->save();
                }
            }

            if ($req->emails) {
                foreach ($req->emails as $key => $email) {
                    $user_contact = new \App\Models\UserContact();

                    if (isset($email['id'])) {
                        $user_contact = \App\Models\UserContact::find($email['id']);
                    }

                    $user_contact->user_id = $req->id;
                    $user_contact->type = 'email';
                    $user_contact->value = $email['value'];
                    $user_contact->save();
                }
            }

            if ($req->whatsapp) {
                foreach ($req->whatsapp as $key => $whatsapp) {
                    $user_contact = new \App\Models\UserContact();

                    if (isset($whatsapp['id'])) {
                        $user_contact = \App\Models\UserContact::find($whatsapp['id']);
                    }

                    $user_contact->user_id = $req->id;
                    $user_contact->type = 'whatsapp';
                    $user_contact->value = $whatsapp['value'];
                    $user_contact->save();
                }
            }

            // update pro_user_info
            // get pro_user_info if not exist create new one if exist update it
            if (in_array($req->usertype, $this->proUsersTypes)) {
                $pro_user_info = \App\Models\pro_user_info::where('user_id', $req->id)->first();

                if (!$pro_user_info) {
                    $validatorArr['company'] = 'required|string|max:255';
                    $validatorArr['city'] = 'nullable|integer|max:255';
                    $validatorArr['address'] = 'nullable|string|max:255';
                    $validatorArr['website'] = 'nullable|string|max:255';
                    $validatorArr['company_image_id'] = 'integer|nullable';
                    $validatorArr['company_banner_id'] = 'integer|nullable';
                    $validatorArr['company_video_id'] = 'integer|nullable';
                    $validatorArr['company_videoembed'] = 'integer|nullable';
                    $validatorArr['company_audio_id'] = 'integer|nullable';

                    // validate request data
                    $validator = Validator::make($req->all(), $validatorArr);

                    // check if validation fails
                    if ($validator->fails()) {
                        // return response()->json(['error' => $validator->errors()], 400);
                        return $this->errorResponse($validator->errors(), 400);
                    }

                    $pro_user_info = new \App\Models\pro_user_info();
                }

                $pro_user_info->user_id = $req->id ?? $pro_user_info->user_id;
                $pro_user_info->address = $req->address ?? $pro_user_info->address;
                $pro_user_info->city = $req->city ?? $pro_user_info->city;
                $pro_user_info->company = $req->company ?? $pro_user_info->company;
                $pro_user_info->website = $req->website ?? $pro_user_info->website;
                $pro_user_info->image = $req->company_image_id ?? $pro_user_info->image;
                $pro_user_info->probannerimg = $req->company_banner_id ?? $pro_user_info->probannerimg;
                $pro_user_info->video = $req->company_video_id ?? $pro_user_info->video;
                $pro_user_info->audio = $req->company_audio_id ?? $pro_user_info->audio;
                $pro_user_info->videoembed = $req->company_videoembed ?? $pro_user_info->videoembed;
                $pro_user_info->save();
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);

            DB::rollBack();
        }

        DB::commit();
        (new LogActivity())->addToLog($user->id, $req);

        $query = \App\Models\User::select('users.id', 'users.firstname', 'users.lastname', DB::raw('CONCAT(users.firstname," ",users.lastname) as fullname'), DB::raw('COUNT(ads.id) as nbr_ads'), 'auth_type.designation as auth_type_designation', 'user_type.designation as user_type_designation', 'users.username', 'users.email', 'pro_user_info.company', 'users.phone', 'users.usertype', 'users.birthdate', 'users.created_at', 'users.updated_at', 'users.expiredate', 'users.authtype', 'users.assigned_ced', 'users.status', 'users.coins')
            ->leftJoin('user_type', 'users.usertype', '=', 'user_type.id')
            ->leftJoin('auth_type', 'users.authtype', '=', 'auth_type.id')
            ->leftJoin('pro_user_info', 'users.id', '=', 'pro_user_info.user_id')
            ->leftJoin('ads', 'users.id', '=', 'ads.id_user')
            ->groupBy('users.id')
            ->where('users.id', $req->id)
            ->first();

        return $this->showAny([
            'message' => 'User updated successfully',
            'user' => $query
        ]);
    }

    public function getUser(Request $req)
    {

        try{
            // add user id to request
            //$req->request->add(['id' => $id]);

            // validate request
            $validator = Validator::make($req->all(), [
                'id' => 'required|integer'
            ]);

            // build query using $data
            $user = \App\Models\User::select('users.id', 'users.firstname', 'users.lastname', 'users.username', 'users.assigned_ced', 'users.email', 'users.phone', 'users.usertype', 'users.birthdate', 'users.created_at', 'users.updated_at', 'users.expiredate', 'users.authtype', 'users.assigned_user', 'user_info.bio', 'user_info.avatar', 'user_info.gender', 'user_info.avatar', 'user_info.likes', 'pro_user_info.address', 'pro_user_info.city', 'pro_user_info.video', 'pro_user_info.audio', 'pro_user_info.image', 'pro_user_info.company', 'pro_user_info.website', 'pro_user_info.probannerimg')
                ->leftJoin('user_info', 'user_info.user_id', '=', 'users.id')
                ->leftJoin('pro_user_info', 'pro_user_info.user_id', '=', 'users.id')
                ->where('users.id', '=' , $req->id)
                ->first();


            // $query = DB::select('CALL getUsersAllInfo(?)', array($req->id));

            // return the user if exists
            // $user = $query[0];

            if (!$user) {
                return $this->errorResponse('User not found!', 404);
            }

            // get the avatar image and add it to the object
            $user->avatar = \App\Models\media::select('*')
                ->where('id', '=' , $user->avatar)
                ->first();

            // get the company image and add it to the object
            if (in_array($user->usertype, $this->proUsersTypes)) {
                $user->image = \App\Models\media::select('*')
                    ->where('id', '=' , $user->image)
                    ->first();

                $user->video = \App\Models\media::select('*')
                    ->where('id', '=' , $user->video)
                    ->first();

                $user->audio = \App\Models\media::select('*')
                    ->where('id', '=' , $user->audio)
                    ->first();

                $user->probannerimg = \App\Models\media::select('*')
                    ->where('id', '=' , $user->probannerimg)
                    ->first();
            }

            // get user contacts
            $user->contacts = DB::select("select * from user_contacts where user_id = $req->id");

            return $this->showAny($user);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);

            DB::rollBack();
        }
    }

    public function getHeaderUser(Request $req)
    {
        $user = \App\Models\User::select('users.email', DB::raw("CONCAT(media.path,media.filename,'.',media.extension) AS avatar"), 'users.username', 'users.firstname', 'users.lastname', 'users.id', 'users.usertype')
            ->leftJoin('user_info', 'user_info.user_id', '=', 'users.id')
            ->leftJoin('media', 'user_info.avatar', '=', 'media.id')
            ->where('users.id', '=', $req->id)->first();
        if (!$user) {
            return $this->errorResponse('User not found!', 404);
        }

        return $this->showAny($user);
    }

    public function changePassword(Request $req, $id)
    {

        // add id to request
        $req->request->add(['id' => $id]);

        // validate request
        $validator = Validator::make($req->all(), [
            'id' => 'required|integer',
            'password' => 'required|string|min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|string|min:6',
        ]);

        // return error if validation fails
        if ($validator->fails()) {
            // return response()->json([
            //         'success' => false,
            //         'error' => $validator->errors()
            //     ], 400);
            return $this->errorResponse($validator->errors(), 400);
        }

        // get user
        $user = \App\Models\User::find($req->id);

        // return error if user not found
        if (!$user) {
            return $this->errorResponse('User not found!', 404);
        }

        // update password
        $user->password = Hash::make($req->password);
        $save = $user->save();

        if ($save) {
            (new LogActivity())->addToLog($user->id, $req);
        }


        // emove all user tokens
        $auth = request()->user();
        $user->tokens()->delete();

        return $this->showAny('Password changed successfully');
    }

    public function updateState(Request $req, $id)
    {

        // add id to request
        $req->request->add(['id' => $id]);


        // validate request
        $validator = Validator::make($req->all(), [
            'id' => 'required|integer',
            'status' => 'required'
        ]);

        // return error if validation fails
        if ($validator->fails()) {
            // return response()->json([
            //         'success' => false,
            //         'error' => $validator->errors()
            //     ], 400);
            return $this->errorResponse($validator->errors(), 400);
        }

        // get user
        $user = \App\Models\User::find($req->id);

        // return error if user not found
        if (!$user) {
            return $this->errorResponse('User not found!', 404);
        }

        // update state
        $user->status = $req->status;
        $save = $user->save();
        // Add to log
        (new LogActivity())->addToLog($user->id, $req);

        if ($save) {
            $data = [
                'name' => $user->username,
                'uid' => $user->id,
                'body' => auth()->user()->username . ' a modifié le status de l\'utilisateur : ' . $user->username,
                'thanks' => 'Merci',
                'text' => 'Visualiser le compte',
                'url' => url('/admin/users'),
                'subject_id' => $req->id,
                'notification_flag' => 'info'
            ];
            Notification::send(auth()->user(), new AnnounceNotifications($data));
        }

        // return success
        // return response()->json([
        //     'success' => true,
        //     'message' => 'State updated successfully'
        // ]);
        return $this->showAny('State updated successfully');
    }

    public function getUserStatus(Request $req, $id)
    {
        // add id to request
        $req->request->add(['id' => $id]);
        // validate request
        $validator = Validator::make($req->all(), [
            'id' => 'required|integer'
        ]);

        // return error if validation fails
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        // get user
        $user = \App\Models\User::find($req->id);

        // return error if user not found
        if (!$user) {
            return $this->errorResponse('User not found!', 404);
        }

        return $this->showAny($user->status);
    }

    public function getUserContracts(Request $req, $id)
    {

        // add id to request
        $req->request->add(['id' => $id]);

        // validate request
        $validator = Validator::make($req->all(), [
            'id' => 'required|integer'
        ]);

        // return error if validation fails
        if ($validator->fails()) {
            // return response()->json([
            //     'success' => false,
            //     'error' => $validator->errors()
            // ], 400);
            return $this->errorResponse($validator->errors(), 400);
        }

        $contracts = \App\Models\Contract::select('*')
            ->where('user_id', $req->id)
            ->orderBy('id', 'desc')
            ->get();

        // To check with Abdelkodoss
        // $contracts = DB::select('CALL getUsersAllInfo(?)',array($req->id));


        // check if contract is still valid
        foreach ($contracts as $contract) {
            $contract->valid = $contract->isValid();
        }

        // return success
        // return response()->json([
        //     'success' => true,
        //     'data' => $contracts
        // ]);
        return $this->showAny($contracts);
    }

    public function getUserLastContract(Request $req, $id)
    {

        // add id to request
        $req->request->add(['id' => $id]);

        // validate request
        $validator = Validator::make($req->all(), [
            'id' => 'required|integer'
        ]);

        // return error if validation fails
        if ($validator->fails()) {

            return $this->errorResponse($validator->errors(), 400);
        }

        // get user
        $contract = \App\Models\Contract::where('user_id', $req->id)
            ->orderBy('id', 'desc')
            ->first();

        // return error if user contract is not found
        if (!$contract) {
            // return response()->json([
            //     'success' => true,
            //     'data' => 'Contract not found!'
            // ], 200);
            return $this->showAny('Contract not found!');
        }

        // check if contract has a contract_file
        if ($contract->contract_file) {
            $contract->contract_file = \App\Models\media::select('*')
                ->where('id', $contract->contract_file)
                ->first();
        }

        $contract->valid = $contract->isValid();

        // return success
        // return response()->json([
        //     'success' => true,
        //     'data' => $contract
        // ]);
        return $this->showAny($contract);
    }

    public function createContract(Request $req, $id)
    {


        // add id to request
        $req->request->add(['user_id' => $id]);

        // validate request
        $validator = Validator::make($req->all(), [
            'user_id' => 'required|integer',
            'assigned_user' => 'nullable|integer',
            'comment' => 'string|nullable',
            'price' => 'required|numeric',
            'plan_id' => 'required|integer',
            'ltc_nbr' => 'required|integer',
            'ads_nbr' => 'required|integer',
            'date' => 'required|date',
            'duration' => 'required|integer',
            'contract_file' => 'integer|nullable'
        ]);

        // return error if validation fails
        if ($validator->fails()) {
            // return response()->json([
            //     'success' => false,
            //     'error' => $validator->errors()
            // ], 400);
            return $this->errorResponse($validator->errors(), 400);
        }

        // check if user_id is valid
        $user = \App\Models\User::find($req->user_id);






        // return error if user not found
        if (!$user) {
            // return response()->json([
            //     'success' => false,
            //     'error' => 'User not found!'
            // ], 404);
            return $this->errorResponse('User not found!', 404);
        }



        // check if assigned_user is valid and is a comercial (usertype = 7)
        /*$assigned_user = \App\Models\User::find($req->assigned_user);

        // return error if user not found
        if (!$assigned_user) {
            // return response()->json([
            //     'success' => false,
            //     'error' => 'Assigned user not found!'
            // ], 404);
            return $this->errorResponse('Assigned user not found!', 404);
        }

        if ($assigned_user->usertype != 7) {
            // return response()->json([
            //     'success' => false,
            //     'error' => 'Assigned user is not a comercial!'
            // ], 404);
            return $this->errorResponse('Assigned user is not a comercial!', 404);
        }


        if (!$user->coins) {
            return $this->errorResponse('User has no coins!', 409);
        }*/



        // check if plan_id is valid
        $plan = \App\Models\Plan::find($req->plan_id);

        // return error if user not found
        if (!$plan) {
            // return response()->json([
            //     'success' => false,
            //     'error' => 'Plan not found!'
            // ], 404);
            return $this->errorResponse('Plan not found!', 404);
        }


        $contract = new \App\Models\Contract();

        try {
            $coinsManager = new  \App\Lib\CoinsManager();

            $coinsManager->transaction(
                $req->user_id,
                $req->ltc_nbr,
                'new_contract',
                'new contract',
                function ($transaction) use ($req, $contract, &$user_new_balance) {
                    $contract->user_id = $req->user_id;
                    $contract->assigned_user = $req->assigned_user;
                    $contract->comment = $req->comment;
                    $contract->price = $req->price;
                    $contract->plan_id = $req->plan_id;
                    $contract->ltc_nbr = $req->ltc_nbr;
                    $contract->ads_nbr = $req->ads_nbr;
                    $contract->date = $req->date;
                    $contract->duration = $req->duration;
                    $contract->contract_file = $req->contract_file;
                    $contract->save();

                    $contract->user_new_balance = $transaction->user_new_balance;

                    // check if contract is craeted
                    if (!$contract) {
                        return false;
                    } else {

                        $data = [
                            'name' => \App\Models\User::find($req->user_id)->username,
                            'uid' => $req->user_id,
                            'body' => 'Un nouveau contrat a été créé pour l\'utilisateur ' . User::find($req->user_id)->username,
                            'text' => 'Consulter le compte',
                            'url' => url('/admin/users'),
                            'subject_id' => $req->id,
                            'notification_flag' => 'info'
                        ];

                        Notification::send(auth()->user(), new AnnounceNotifications($data));
                        // Add to log
                        (new LogActivity())->addToLog($req->user_id, $req);
                    }

                    return true;
                }
            );
        } catch (\Exception $e) {
            // return response()->json([
            //     'success' => false,
            //     'error' => $e->getMessage()
            // ], 400);
            return $this->errorResponse($e->getMessage(), 500);
        }

        // return success with contract
        // return response()->json([
        //     'success' => true,
        //     'data' => $contract
        // ]);


        return $this->showAny($contract);

        $Userinfo = User::select('email', 'username', 'coins')->where('id', '=', $req->user_id)->first();
        $price = $contract->price;
        $assigned_user = $contract->assigned_user;
        $ltc_nbr = $contract->ltc_nbr;
        $ads_nbr = $contract->ads_nbr;
        $duration = $contract->duration;


        Mail::send(new SimpleMail(
            [
                "to" => $Userinfo['email'],
                "subject" => "Vous avez une nouvelle contrat !!",
                "view" => "emails.Contract.fr",
                "data" => [
                    "username" => $Userinfo['username'],
                    "coins" => $ltc_nbr,
                    "prix" => $price,
                    "ads_nbr" => $ads_nbr,
                    "duration" => $duration,
                    "commercial" => $assigned_user,
                ]
            ]
        ));
    }

    public function updateContract(Request $req, $id)
    {

        // add id to request
        $req->request->add(['user_id' => $id]);

        // validate request
        $validator = Validator::make($req->all(), [
            'id' => 'required|integer',
            'user_id' => 'integer|nullable',
            'assigned_user' => 'integer|nullable',
            'comment' => 'string|nullable',
            'price' => 'numeric|nullable',
            'plan_id' => 'integer|nullable',
            'ltc_nbr' => 'integer|nullable',
            'ads_nbr' => 'integer|nullable',
            'date' => 'date|nullable',
            'duration' => 'integer|nullable', //days
            'contract_file' => 'integer|nullable'
        ]);

        // return error if validation fails
        if ($validator->fails()) {
            // return response()->json([
            //     'success' => false,
            //     'error' => $validator->errors()
            // ], 400);
            return $this->errorResponse($validator->errors(), 400);
        }

        // get contract
        $contract = \App\Models\Contract::find($req->id);

        // return error if contract not found
        if (!$contract) {
            // return response()->json([
            //     'success' => false,
            //     'error' => 'Contract not found!'
            // ], 404);
            return $this->errorResponse('Contract not found!', 404);
        }

        // check if user_id is valid
        if ($req->user_id) {
            $user = \App\Models\User::find($req->user_id);

            // return error if user not found
            if (!$user) {
                // return response()->json([
                //     'success' => false,
                //     'error' => 'User not found!'
                // ], 404);
                return $this->errorResponse('User not found!', 404);
            }
        }

        // check if assigned_user is valid and is a comercial (usertype = 7)
        /*if ($req->assigned_user) {
            $assigned_user = \App\Models\User::find($req->assigned_user);

            // return error if user not found
            if (!$assigned_user) {
                // return response()->json([
                //     'success' => false,
                //     'error' => 'Assigned user not found!'
                // ], 404);
                return $this->errorResponse('Assigned user not found!', 404);
            }

            if ($assigned_user->usertype != 7) {
                // return response()->json([
                //     'success' => false,
                //     'error' => 'Assigned user is not a comercial!'
                // ], 404);
                return $this->errorResponse('Assigned user is not a comercial!', 404);
            }
        }*/

        // check if plan_id is valid
        if ($req->plan_id) {
            $plan = \App\Models\Plan::find($req->plan_id);

            // return error if user not found
            if (!$plan) {
                // return response()->json([
                //     'success' => false,
                //     'error' => 'Plan not found!'
                // ], 404);
                return $this->errorResponse('Plan not found!', 404);
            }
        }

        $ltc_to_add = $req->ltc_nbr - $contract->ltc_nbr;

        $coinsManager = new  \App\Lib\CoinsManager();

        $coinsManager->transaction(
            $req->user_id,
            $ltc_to_add,
            'update_contract',
            "update contract, old contract coins is $contract->ltc_nbr ltc, new contract coins is $req->ltc_nbr ltc, update the user coins balance to $req->ltc_nbr - $contract->ltc_nbr = $ltc_to_add ltc",
            function ($transaction) use ($req, $contract) {

                // update contract
                $contract->user_id = $req->user_id ?? $contract->user_id;
                $contract->assigned_user = $req->assigned_user ?? $contract->assigned_user;
                $contract->comment = $req->comment ?? $contract->comment;
                $contract->price = $req->price ?? $contract->price;
                $contract->plan_id = $req->plan_id ?? $contract->plan_id;
                $contract->ltc_nbr = $req->ltc_nbr ?? $contract->ltc_nbr;
                $contract->ads_nbr = $req->ads_nbr ?? $contract->ads_nbr;
                $contract->date = $req->date ?? $contract->date;
                $contract->duration = $req->duration ?? $contract->duration;
                $contract->contract_file = $req->contract_file ?? $contract->contract_file;
                $contract->save();

                $contract->user_new_balance = $transaction->user_new_balance;

                if ($contract) {
                    $data = [
                        'name' => \App\Models\User::find($req->user_id)->username,
                        'uid' => $req->user_id,
                        'body' => auth()->user()->username . ' a modifié le contrat de l\'utilisateur ' . $req->user_id,
                        'thanks' => 'Merci',
                        'text' => 'Consulter le compte d\'utilisateur  : ' . User::find($req->user_id)->username,
                        'url' => url('/admin/users'),
                        'subject_id' => $req->id,
                        'notification_flag' => 'info'
                    ];
                    Notification::send(auth()->user(), new AnnounceNotifications($data));

                    return true;
                } else {
                    return false;
                }
            }
        );

        // return success with contract
        // return response()->json([
        //     'success' => true,
        //     'data' => $contract
        // ]);
        return $this->showAny($contract);
    }

    public function getUserTransactions(Request $req, $id)
    {

        // add the id to the request
        $req->request->add(['user_id' => $id]);

        // validate request
        $validator = Validator::make($req->all(), [
            'user_id' => 'required|integer'
        ]);

        // return error if validation fails
        if ($validator->fails()) {
            // return response()->json([
            //     'success' => false,
            //     'error' => $validator->errors()
            // ], 400);
            return $this->errorResponse($validator->errors(), 400);
        }

        // get user
        $user = \App\Models\User::find($req->user_id);

        // return error if user not found
        if (!$user) {
            // return response()->json([
            //     'success' => false,
            //     'error' => 'User not found!'
            // ], 404);
            return $this->errorResponse('User not found!', 404);
        }

        // get user transactions
        $transactions = \App\Models\Transaction::where('user_id', $req->user_id)->get();

        // return transactions
        // return response()->json([
        //     'success' => true,
        //     'data' => $transactions
        // ]);
        return $this->showAny($transactions);
    }

    public function getUserCoins($id)
    {
        if ($id == null) {
            return $this->errorResponse('id is required', 400);
        }

        $user = \App\Models\User::where('id', $id)->first();


        // if user is not found, return error
        if (!$user) {
            return $this->errorResponse('User not found!', 404);
        }

        $coins = $user->coins;

        return $this->showAny($coins);
    }
}
