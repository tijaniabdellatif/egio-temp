<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class cats extends Model
{
    use HasFactory;

    protected $table = 'cats';
    public $timestamps = false;

    /*protected $casts = [
        'fields' => 'object'
    ];*/

    protected $guarded = [];




    // relations between Parentcat and cats
    public function parentCat() {
        return $this->belongsTo(cats::class,'parent_cat');
    }



    public function childCat() {
        return $this->hasMany(cats::class,'parent_cat');
    }

    public function ads(){

        return $this->hasMany(ads::class,'catid');
    }
}
