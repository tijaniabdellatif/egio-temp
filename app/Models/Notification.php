<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $table = "notifications";
    protected $fillable =[
        'subject_id','body','text','url','notification_flag','stopNotify'
    ];
}
