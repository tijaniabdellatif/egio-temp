<?php

namespace App\Providers;

use App\Events\AdsExpired;
use App\Listeners\AdsExpiredAt;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\ads;
use App\Models\ad_media;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        \SocialiteProviders\Instagram\InstagramExtendSocialite::class.'@handle',
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        AdsExpired::class => [

              AdsExpiredAt::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

    }
}
