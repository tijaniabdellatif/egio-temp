<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ad_media extends Model
{
    use HasFactory;
    protected $table = 'ad_media';
    public $timestamps = false;

    protected $guarded = [

    ];


    // Relation between media and ads
    public function medias() {
        return $this->belongsToMany(medias::class,'ad_media','ad_id',"media_id");
    }
}
