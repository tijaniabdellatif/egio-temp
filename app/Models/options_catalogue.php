<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class options_catalogue extends Model
{
    use HasFactory;
    protected $table = 'options_catalogue';
    public $timestamps = false;


    // Relation between options and options catalogue
    public function optionType() {
        return $this->belongsTo(option_type::class,'type_id');
    }

    public function option(){

        return $this->hasMany(options::class,'option_id');
    }

    /**
     * ManytoMany
     *
     * @return void
     */
   public function ads(){
    return $this->belongsToMany(ads::class,'options','option_id','ad_id');

   }

   
}
