<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ads;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CronJobs extends Controller
{



     public function getCron(User $user){


    //    $load = $user->load('ads');

    //     dd($load->get());



     }
}
