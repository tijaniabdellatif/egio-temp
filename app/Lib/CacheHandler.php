<?php

namespace App\Lib;


use Illuminate\Support\Facades\Artisan;
use Spatie\ResponseCache\Facades\ResponseCache;


trait CacheHandler {


    public static function boot(){


        parent::boot();

        static::created(function($instance){

            // ResponseCache::clear();

        });
        static::updated(function ($instance) {
            // ResponseCache::clear();

        });

        static::deleted(function ($instance) {
            // ResponseCache::clear();

        });

    }
}
