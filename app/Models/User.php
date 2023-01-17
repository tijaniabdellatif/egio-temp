<?php

namespace App\Models;

use Closure;
use App\Lib\CacheHandler;
use App\Models\user_type;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\ResponseCache\Facades\ResponseCache;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Propaganistas\LaravelPhone\PhoneNumber;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;


    protected $table = 'users';

    protected $guarded = [];


    protected $hidden = [

        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    // protected function phone(): Attribute
    // {
    //     return Attribute::set(
    //         set: fn ($value) => phone($value, ["MA","FR"])
    //     );

    //     if($this->attributes['phone']->isDirty()){

    //         return Attribute::set(
    //             set: fn ($value) => phone($value, ["MA","FR"])
    //         );
    //     }

    // }

    protected function getPhoneAttribute($value)
    {

        return '+' . substr(phone($value, ["MA", "FR"]), 1);
    }

    public static function procedure(Closure $closure)
    {
        return  $closure(User::select('CALL getALL'));
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'user_id');
    }


    public function ads()
    {
        return $this->hasMany(ads::class, 'id_user');
    }

    public function actions()
    {
        return $this->belongsToMany(Action::class, 'users_actions', "user_id", "action_id");
    }

    //Relation Between Contract and assigned_user (user_id)
    public function assigned_user_contracts()
    {
        return $this->hasMany(Contract::class, 'user_id');
    }


    //relation entre user et user_types
    public function usersTypes()
    {
        return $this->belongsTo(user_type::class, 'usertype');
    }

    // Reflexive relationship between user and assigned user
    public function parent()
    {
        return $this->belongsTo(User::class, 'assigned_user');
    }

    public function child()
    {
        return $this->hasMany(User::class, 'assigned_user');
    }

    // Relation between proUser and Users
    public function proUser()
    {
        return $this->belongsTo(pro_user_info::class, 'user_id');
    }

    // Relation entre les transactions and Users
    public function Transaction()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    //Relation entre userContatct and Users
    public function UserContact()
    {
        return $this->hasMany(UserContacts::class, 'user_id');
    }

    //Relation entre User and UserInfo
    public function UserInfo()
    {
        return $this->hasOne(user_info::class, 'user_id', 'id');
    }

    //Relation between proUser and userInfo through Users
    public function proUserInfo()
    {
        return $this->hasOneThrough(user_info::class, pro_user_info::class, 'user_id', 'user_id', 'id', 'id');
    }


    public function activity()
    {
        return $this->hasMany(LogActivity::class, 'user_id');
    }


    public function setFirstname($firstname)
    {
        $this->attributes['firstname'] = strtolower($firstname);
    }

    public function getFirstname($firstname)
    {
        return ucfirst($firstname);
    }
}