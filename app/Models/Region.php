<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Region extends Model
{
    use SpatialTrait;
    use HasFactory;
    protected $table = 'regions';
    public $timestamps = false;
    protected $spatialFields = [
        'coordinates'
    ];


    protected $hidden = [

        
        
    ];

    //Relation Between countries and provinces
    public function country(){
           return $this->belongsTo(countries::class,'country_id');
    }
    
    //Relation Between countries and provinces
    public function provinces(){
        return $this->hasMany(provinces::class,'region_id');
    }


}
