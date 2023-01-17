<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnnoncesNotificationsController extends Controller
{
    public function SendNotification(Request $request) {
        $annonce = [
            'title' => 'Nouvelle Annonce',
            'description' => 'Votre nouvelle annonce est publiée avec succès',
            'url' => ''
        ];
    }
}
