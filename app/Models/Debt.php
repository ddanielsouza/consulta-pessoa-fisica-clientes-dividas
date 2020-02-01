<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Debt extends Model
{
    use \App\Helpers\ISOSerialization;

    protected $table = "debts";

    protected $fillable = [
        'id',
        'client_id',
        'description',
        'startDate',
        'datePayment',
        'initialAmount',
        'paymentAmount',
        'isActive',
    ];

    protected $casts = [
        'isActive' => 'boolean',
        'datePayment' => 'datatime',
        'startDate' => 'datetime',
    ];

    public function setDatePaymentAttribute($value)
    {
        $this->attributes['datePayment'] = is_string($value) ? new DateTime($value) : $value; 
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['startDate'] = is_string($value) ? new DateTime($value) : $value; 
    }
}
