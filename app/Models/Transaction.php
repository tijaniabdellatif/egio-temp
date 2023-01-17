<?php

namespace App\Models;

use App\Lib\CacheHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    public $timestamps = false;

    use CacheHandler;

    // Relation entre les transactions and Users
    public function UserTransaction() {
        return $this->belongsTo(User::class,'user_id');
    }
}
