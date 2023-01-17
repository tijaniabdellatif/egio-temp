<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class langController extends ApiController
{
    public function changelang(Request $req){
        $lang = $req->lang;
        if (! in_array($lang, ['ar', 'fr'])) {
            return $this->errorResponse("Cette langue n'est pas disponible", 404);
        }
        Session::put('lang', $lang);

        return $this->showAny(App()->getLocale());
    }
}
