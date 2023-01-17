<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class options extends Model
{
    use HasFactory;
    protected $table = 'options';
    public $timestamps = false;

    protected $hidden = [

         'created_at',
         'updated_at'

    ];


    // Relation between ads and options
    public function ad() {
        return $this->belongsTo(ads::class,'ad_id');
    }

    // Relation between options and options catalogue
    public function catalogueOptions() {
        return $this->belongsTo(options_catalogue::class,'option_id');
    }

    public function optionType(){

         return $this->belongsTo(option_type::class,'option_id');
    }


}
