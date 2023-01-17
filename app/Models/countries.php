<?php

namespace App\Models;

use App\Models\Region;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class countries extends Model
{
    use HasFactory;
    use SpatialTrait;

    protected $table = 'countries';
    public $timestamps = false;
    protected $spatialFields = [
        'coordinates'
    ];


    public function regions(){

        return $this->hasMany(Region::class,'country_id');
    }
}
