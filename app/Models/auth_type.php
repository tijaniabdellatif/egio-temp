<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class auth_type extends Model
{
    use HasFactory;
    protected $table = 'auth_type';
    public $timestamps = false;
}
