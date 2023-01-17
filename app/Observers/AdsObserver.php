<?php

namespace App\Observers;

use App\Http\Controllers\Api\ApiController;
use App\Models\ads;

class AdsObserver extends ApiController
{
    /**
     * Handle the ads "created" event.
     *
     * @param  \App\Models\ads  $ads
     * @return void
     */
    public function created(ads $ads)
    {
        //
    }

    /**
     * Handle the ads "updated" event.
     *
     * @param  \App\Models\ads  $ads
     * @return void
     */
    public function updated(ads $ads)
    {



    }

    public function updating(ads $ads)
    {

    }

    /**
     * Handle the ads "deleted" event.
     *
     * @param  \App\Models\ads  $ads
     * @return void
     */
    public function deleted(ads $ads)
    {
        //
    }

    /**
     * Handle the ads "restored" event.
     *
     * @param  \App\Models\ads  $ads
     * @return void
     */
    public function restored(ads $ads)
    {
        //
    }

    /**
     * Handle the ads "force deleted" event.
     *
     * @param  \App\Models\ads  $ads
     * @return void
     */
    public function forceDeleted(ads $ads)
    {
        //
    }
}
