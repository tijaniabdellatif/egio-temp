<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Propaganistas\LaravelPhone\PhoneNumber;


class UserContact extends Model
{

    use HasFactory;

    protected $table = 'user_contacts';
    public $timestamps = false;
    protected $fillable = ['user_id','value','type'];

    protected function phone(): Attribute
    {

        if ($this->attributes['type'] === "whatsapp" ) {
            return Attribute::set(
                set: fn ($value) => phone($value, ["MA","FR"])
            );
        }


        if($this->attributes['type']->isDirty()){
            return Attribute::set(
                set: fn ($value) => phone($value, ["MA","FR"])
            );


        }
    }

    protected function getPhoneAttribute($value){

        return substr(PhoneNumber::make($value, 'MA'), 0);

   }
    // Relation entre UserContacts and User
    public function UserToContact()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
