<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_resets';

    protected $guarded = [];

    // primary key is email
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'email';

}
