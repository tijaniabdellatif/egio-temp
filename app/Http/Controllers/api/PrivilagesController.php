<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\user_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PrivilagesController extends ApiController
{

    // filter actions query
    public function filterActions(Request $request){
        // id
        // controller
        // action
        // description

        // build query using $data
        $query = \App\Models\Action::select('*')
                    ->orderBy('id', 'desc');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'controller' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'action' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'description' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ]
            ],
            true
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
            // // get sql statement bind parameters
            // $bindings = $query->getBindings();

            // dd($sql,$bindings);

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

    // filter types
    public function filterRoles(Request $request){
        // id
        // name
        // designation

        // build query using $data
        $query =  Role::select('*')
                    ->orderBy('id', 'desc');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'name' => [
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ]
            ]
        , true);

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
            // // get sql statement bind parameters
            // $bindings = $query->getBindings();

            // dd($sql,$bindings);

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

    // filter types
    public function filterTypeActions(Request $request){
        // id
        // type_id
        // action_id

        // build query using $data
        $query = \App\Models\TypeAction::select('*')
                    ->orderBy('id', 'desc');

        // the filter helper function
        $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'type_id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'action_id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ]
            ]
        , true);

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
            // // get sql statement bind parameters
            // $bindings = $query->getBindings();

            // dd($sql,$bindings);

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

    public function GetPermmissionsByRole(Request $request){
        try {
            if(!isset($request->id)) return $this->errorResponse("bad request", 400);
            $id = $request->id;
            $data = Permission::select('permissions.id','permissions.name',DB::raw('!ISNULL(role_has_permissions.permission_id) as checked'))
                        ->leftJoin("role_has_permissions",function($q) use ($id){
                            $q->on("role_has_permissions.permission_id","=","permissions.id")
                            ->where('role_has_permissions.role_id', '=', $id);
                        })->groupBy("permissions.id")->orderBy("permissions.id")->get();
            return $this->showAny($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

    }

    public function addPermissions(Request $request){
        try {
            if(!isset($request->id)||!isset($request->data)||(isset($request->data)&&!is_array($request->data)))
                return $this->errorResponse("bad request", 400);

            $permissions = [];
            foreach ($request->data as $key => $value) {
                if($value['checked'] == true){
                    $permissions [] = $value['name'];
                }
            }

            $check = Role::find($request->id)->syncPermissions($permissions);
            if(!$check){
                return $this->errorResponse('Somthing wrong!',409);
            }
            return $this->showAny(null);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function filterUsers(Request $request){
        $query = User::select('users.*' , 'user_type.designation as user_type_designation')->leftJoin('user_type', 'users.usertype', '=', 'user_type.id')
                ->whereNotNull('user_type.role_id')
                ->orderBy('users.id', 'desc');

            // the filter helper function
            $query = queryFilter(
            $request->where, // json data
            $query,
            [], // joins
            [
                'id' => [
                    'type' => 'int',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'username' => [
                    'type' => 'string',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'firstname' => [
                    'type' => 'string',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ],
                'lastname' => [
                    'type' => 'string',
                    'operators' => ['=', '!=','LIKE', 'NOT LIKE'],
                ]
            ]
            , true);

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


                return $this->showAny($result);

            } catch (\Exception $e) {

                return $this->errorResponse($e->getMessage(), 500);
            }
    }


    public function createUsers(Request $req){
        try{
            // validate request
            $validator = Validator::make($req->all(), [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'cpassword' => 'required|string|min:8|same:password',
                'usertype' => 'required|integer'
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // create a new plan
            $user = new User();
            $user->firstname = $req->firstname;
            $user->lastname = $req->lastname;
            $user->username = $req->username;
            $user->email = $req->email;
            $user->usertype = $req->usertype;
            $user->password = Hash::make($req->password);
            $user->status = "10";


            // save plan
            $check =$user->save();

            // check if user is created
            if (!$check) {
                DB::rollBack();
                return $this->errorResponse("Erreur non défini contactez votre administrateur", 400);
            }

            $user_type = user_type::select("roles.name as role")->join('roles','user_type.role_id','=','roles.id')->where('user_type.id','=',$req->usertype)->first();

            if($user_type && $user_type->role){
                $role = $user_type->role;
                $user->assignRole($role);
            };

            $result = User::select('users.*' , 'user_type.designation as user_type_designation')->leftJoin('user_type', 'users.usertype', '=', 'user_type.id')
                ->whereNotNull('user_type.role_id')->where('users.id',"=",$user->id)->first();

            return $this->showAny($result);
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage(),500);
        }
    }

    public function updateUsers(Request $req){

        try{
            // validate request
            $validator = Validator::make($req->all(), [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'string|unique:users,username,'.$req->id . '|nullable',
                'email' => 'string|unique:users,email,'.$req->id . '|nullable',
                'usertype' => 'required|integer',
            ]);

            // check if validation fails
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(),400);
            }

            // get cat
            $user = User::find($req->id);

            // check if plan exists
            if(!$user){
                return $this->errorResponse('User not found',404);
            }

            foreach($user->getRoleNames() as $val){
                $user->removeRole($val);
            }

            // set plan data
            $user->firstname = $req->firstname;
            $user->lastname = $req->lastname;
            $user->username = $req->username;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->usertype = $req->usertype;
            $user->status = "10";

            if(!$user->isDirty()){
                return $this->errorResponse('Rien à modifier',409);
            }
            $user->save();

            $user_type = user_type::select("roles.name as role")->join('roles','user_type.role_id','=','roles.id')->where('user_type.id','=',$req->usertype)->first();

            if($user_type && $user_type->role){
                $role = $user_type->role;
                $user->assignRole($role);
            }

            $newData = User::select('users.*' , 'user_type.designation as user_type_designation')->leftJoin('user_type', 'users.usertype', '=', 'user_type.id')
                             ->whereNotNull('user_type.role_id')->where('users.id',"=",$user->id)->first();

            return $this->showAny($newData);
        }catch(\Throwable $th){

            return $this->errorResponse($th->getMessage(),500);
        }

    }

}
