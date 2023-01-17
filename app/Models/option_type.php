<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class option_type extends Model
{
    use HasFactory;
    protected $table = 'option_type';
    public $timestamps = false;

    public function catalogue(){

          return $this->hasMany(options_catalogue::class,'type_id');
    }

    public function many(){
        return $this->hasManyThrough(options::class, options_catalogue::class, 'type_id', 'option_id', 'id', 'id');
    }

    public function options(){

         return $this->hasMany(options::class,'option_id');
    }
}
