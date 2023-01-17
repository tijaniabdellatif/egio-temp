<?php

namespace App\Models;


use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ads extends Model
{
    use HasFactory;



    protected $table = 'ads';

    protected $guarded = [];



    protected $hidden = [

        'pivot',
        "created_at",
        "updated_at"
    ];




    // Relation between ads and options
    public function options() {
        return $this->hasMany(options::class,'ad_id');
    }

    /**
     * Many to many pivot is options
     */
    public function optionscatalogue(){
        return $this->belongsToMany(options_catalogue::class,'options','ad_id','option_id');
    }

      public function medias(){
        return $this->belongsToMany(media::class, 'ad_media', "ad_id", "media_id");
    }
    // Relation between ads and clicks
    public function clicks() {
        return $this->hasMany(clicks::class,'ad_id');
    }

    //Relation between ads and Nearby Places
    public function nearbyPlaces() {
        return $this->hasMany(nearby_places::class,'ad_id');
    }

    public function user(){

        return $this->belongsTo(User::class,'id_user');
    }

    public function cities(){
          return $this->belongsTo(cities::class,'loccity');
    }

    public function project_priority() {
        return $this->hasMany(project_priority::class,'id');
    }

    public function cats(){

        return $this->belongsTo(cats::class,"catid");
    }


}
