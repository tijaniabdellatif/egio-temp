<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Action;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [

        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
       // Passport::routes();

        //i think we should change the columns of actions table to be { id, name, description }

        // get all actions with user_types
        // $actions = Action::with('user_types')->get();

        // opinion: we need to store the $actions in the memory cache instead of querying the database every time.

        // // loop through actions and check if the type of the authentificated user is allowed to access the action
        // foreach ($actions as $action) {
        //     Gate::define($action->name, function (User $user) use ($action) {
        //         // check if the user has a usertype
        //         if ($user->usertype) {
        //             // check if the user->usertype (id) is exists in the actions->user_types
        //             if ($action->user_types->contains('id', $user->usertype)) {
        //                 return Response::allow();
        //             }
        //         }

        //         // get all names from $action->user_types
        //         $allowed_users = $action->user_types->pluck('name')->toArray();

        //         // return response denied if the user is not allowed to access the action
        //         return Response::deny("You are not allowed to access this action (" . $action->name . ") . Only users with the following types are allowed: " . implode(', ', $allowed_users));
        //     });
        // }

        // --------------------------------- other functionalities i thin we should use theme -----------------------------------------


        // some times like in the case of super admin, we need to grant all permissions to him, after that in the code above we will load all the actions from cache/database and loop through them to check if its allowed to access the action, it will take a few time to complete. but we already know that this kind of users is allowed to access all actions, in this case we can use the next code below to avoid waiting for the database to load all the actions and check if the user is allowed to access the action.
        // Gate::before(function ($user, $ability) {
        //     if ($user->isAdministrator()) {
        //         return true;
        //     }
        // });
       /* Gate::before(function ($user, $ability) {
            if ($user->isAdministrator()) {
                return true;
            }
        }); */


        // ---------------------------------------------------------------- example --------------------------------------------------------------

        // any other action that is not defined in the gates all users allowed to access it.
        Gate::before(function ($user, $ability) {
            // get gates abilities
            $abilities = Gate::abilities();

            // check if the ability is in the gates abilities
            if (!in_array($ability, $abilities)) {
                return Response::allow();
            }
        });

        // create a Gate to allow the superAdmin or the admin to create user
        // Gate::define('create-user', function (User $user) {
        //     if ($user->usertype) {
        //         // 1 => super admin
        //         // 2 => admin
        //         if ($user->usertype == 1 || $user->usertype == 2) {
        //             return Response::allow();
        //         }
        //     }
        //     return Response::deny("You are not allowed to create users.");
        // });

        // Gate::define('add-user', function (User $user) {
        //     if ($user->usertype) {
        //         // 1 => super admin
        //         // 2 => admin
        //         if ($user->usertype == 1 || $user->usertype == 2) {
        //             return Response::allow();
        //         }
        //     }
        //     return Response::deny("You are not allowed to create users.");
        // });

    }
}
