<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogActivity extends Model
{
    use HasFactory;

    protected $table = 'log_activities';
    protected $fillable = [
        'subject', 'subject_id', 'url', 'method', 'ip', 'agent', 'user_id', 'country', 'region', 'lat', 'long'
    ];
    protected $casts = [
        'created_at'  => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    public function setCreatedAtAttribute($date)
    {
        $this->attributes['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
    }


    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }


    public function user()
    {

        return $this->belongsTo(User::class, 'user_id');
    }
}
