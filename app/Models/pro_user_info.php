<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pro_user_info extends Model
{
    use HasFactory;
    protected $table = 'pro_user_info';
    public $timestamps = false;
    protected $fillable = ['id','user_id','address','address','loclng','loclat','city','locdept','locregion','loccountrycode','video','audio','image','company','website','probannerimg','longdesc','videoembed','metatitle'];

    // Relation between proUser and Users
    public function User() {
        return $this->hasMany(User::class,'user_id');
    }

    // Relation between proUser and cities
    public function proUserCity() {
        return $this->hasMany(cities::class,'city');
    }

    // Relation between proUser and Medias
    // public function proBannerImg() {
    //     return $this->hasMany(media::class,'probannerimg');
    // }

}
