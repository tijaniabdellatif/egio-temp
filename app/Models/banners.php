<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class banners extends Model
{
    use HasFactory;
    protected $table = 'banner';
    public $timestamps = false;
}
