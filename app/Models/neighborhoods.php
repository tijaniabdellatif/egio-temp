<?php

namespace App\Models;

use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class neighborhoods extends Model
{
    use HasFactory;
    use SpatialTrait;
    protected $table = 'neighborhoods';
    public $timestamps = false;
    protected $spatialFields = [
        'coordinates',
        'dCoordinates'
    ];

    public function city () {
        return $this->belongsTo(cities::class,'city_id');
    }
}
