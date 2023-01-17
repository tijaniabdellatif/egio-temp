<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nearby_places extends Model
{
    use HasFactory;
    protected $table = 'nearby_places';
    public $timestamps = false;
   
    // Relation between ads and nearby places
    public function ads() {
        return $this->belongsTo(ads::class,'id_ad');
    }

    // Relation between nearbyPlaces and placesType
    public function placesType() {
        return $this->belongsTo(places_type::class,'id_place_type');
    }
}
