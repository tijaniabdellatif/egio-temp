<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class places_type extends Model
{
    use HasFactory;
    protected $table = 'places_type';
    public $timestamps = false;

    // Relation entre nearbyPlaces and placesType
    public function nearbyPlaces() {
        return $this->hasMany(nearby_places::class,'id_place_type');
    }
}
