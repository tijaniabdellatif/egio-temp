<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\Permission\Models\Role;

class user_type extends Model
{
    use HasFactory;
    protected $table = 'user_type';
    public $timestamps = false;

    protected $guarded = [];


    public function user() {

        return $this->hasMany(User::class,'usertype');
    }

    // Relation entre UserTypes and Roles
    public function UserRoles() {
        return $this->belongsTo(Role::class,'id');
    }
}
