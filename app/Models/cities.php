<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;


class cities extends Model
{

    use SpatialTrait;
    use HasFactory;
    protected $table = 'cities';
    public $timestamps = false;
    protected $spatialFields = [
        'coordinates'
    ];

    protected $guarded=[];

    


    
    //Relation Between cities and provinces
    public function provinces() {
        return $this->belongsTo(provinces::class,'province_id');
    }

    // Relation between proUser and cities
    public function proUser() {
        return $this->belongsTo(pro_user_info::class,'city');
    }

}
