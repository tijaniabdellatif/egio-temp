<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class media extends Model
{
    use HasFactory;
    protected $table = 'media';
    public $timestamps = false;


    protected $hidden = [

        'pivot',
        "mediatype"
    ];

    protected $guarded = [

    ];

    public function mediatype () {
        return $this->belongsTo(media_type::class,'media_type');
    }

    // Relation between proUserInfo and Media
    public function proUserImgBanner() {
        return $this->belongsTo(pro_user_info::class,'probannerimg');
    }


    public function mediasFromAds(){
        return $this->belongsToMany(ads::class, 'ad_media', "media_id", "ad_id");
        }


}
