<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Auth;

class UserCoins extends ApiController
{
    public function getUserCoins(Request $request)
    {
        $userId = Auth::user()->id;
        $user = User::select('coins')->where('id', '=', $userId)->first();
        $balance = $user ? $user->coins : 0;

        return $this->showAny($balance);
    }
}
