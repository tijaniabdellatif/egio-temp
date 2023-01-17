<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Laravel\Sanctum\Sanctum;
use App\Observers\UserObeserver;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{



    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);


        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }


    }
}
