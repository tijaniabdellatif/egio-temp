<?php

namespace App\Providers;

use App\Repository\AdsRepository;
use App\Repository\CategoryRepository;
use App\Repository\Interfaces\AdsInterface;
use App\Repository\Interfaces\CategoryInterface;
use App\Repository\Interfaces\UserInterface;
use App\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {


        $this->app->bind(CategoryInterface::class,CategoryRepository::class);
        $this->app->bind(AdsInterface::class,AdsRepository::class);

    }
}
