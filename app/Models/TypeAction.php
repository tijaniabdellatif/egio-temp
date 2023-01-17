<?php

namespace App\Models;

use App\Lib\CacheHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeAction extends Model
{
    use HasFactory;
    use CacheHandler;
    protected $table = 'types_actions';
    public $timestamps = false;
}
