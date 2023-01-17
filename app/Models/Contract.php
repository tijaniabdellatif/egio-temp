<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $table = 'contracts';
    protected $fillable = [
        'user_id',
        'assigned_user',
        'comment',
        'price',
        'plan_id',
        'ltc_nbr',
        'ads_nbr',
        'date',
        'duration',
        'contract_file',
        'active',
        'created_at',
        'updated_at',
    ];

    //Relation Between Contract and user (user_id)
    public function users() {
        return $this->belongsTo(User::class,'user_id');
    }

    //Relation Between Contract and assigned_user (user_id)
    public function assignedusers() {
        return $this->belongsTo(User::class,'assigned_user');
    }



    // add is valid
    public function isValid()
    {

        // id $this->date is null return true
        if ($this->date == null) {
            return true;
        }

        $currentDate = new \DateTime();

        $contractDate = new \DateTime($this->date);

        $contractDuration = $this->duration;

        // add days to contract date
        $endOfContractDate = $contractDate->add(new \DateInterval('P' . $contractDuration . 'D'));

        // check if current date is between contract date and end of contract date
        if ($currentDate >= $contractDate && $currentDate <= $endOfContractDate) {
            return true;
        }

        return false;
    }
}
