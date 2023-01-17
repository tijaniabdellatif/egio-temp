<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class standings extends Model
{
    use HasFactory;
    protected $table = 'standing';
    public $timestamps = false;
}
