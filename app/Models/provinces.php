<?php

namespace App\Models;

use App\Http\Controllers\api\regions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class provinces extends Model
{
    use SpatialTrait;
    use HasFactory;
    protected $table = 'provinces';
    public $timestamps = false;
    protected $spatialFields = [
        'coordinates'
    ];




    public function region() {
        return $this->belongsTo(Region::class,'region_id');
    }

    //Relation Between provinces and cities
    public function cities() {
        return $this->hasMany(cities::class,'province_id');
    }

}
