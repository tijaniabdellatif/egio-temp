<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class media_type extends Model
{
    use HasFactory;
    protected $table = 'media_type';
    public $timestamps = false;


    public function medias() {
        return $this->hasMany(media::class,'media_type');
    }
}
