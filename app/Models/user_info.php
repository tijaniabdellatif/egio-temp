<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_info extends Model
{
    use HasFactory;
    protected $table = 'user_info';
    protected $fillable = [

        "user_id",
        "bio",
        "gender",
        "avatar",
        "likes"
    ];
    public $timestamps = false;



    //Relation entre User et UserInfo
    public function Users() {
        return $this->belongsTo(User::class,'user_id');
    }



}
