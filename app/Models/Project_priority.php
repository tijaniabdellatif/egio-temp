<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_priority extends Model
{
    use HasFactory;
    protected $table = 'project_priority';
    public $timestamps = false;

    protected $hidden = [

        'created_at',
        'updated_at'

   ];

   public function ads() {
    return $this->belongsTo(ads::class,'ad_id');
}
}
