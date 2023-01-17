<?php

namespace App\Models;

use App\Lib\CacheHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class types extends Model
{
    use HasFactory;

    use CacheHandler;
    protected $table = 'proprety_types';
    public $timestamps = false;
}
