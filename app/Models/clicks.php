<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clicks extends Model
{
    use HasFactory;
    protected $table = 'clicks';
    public $timestamps = false;

    // Relation between clicks and ads 
    public function ads() {
        return $this->belongsTo(ads::class,'ad_id');
    }
}
