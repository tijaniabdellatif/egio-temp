<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;
    protected $table = 'actions';
    protected $guarded = [];


    public function users(){

          return $this->belongsToMany(User::class,"users_actions","user_id","action_id");
    }
}
