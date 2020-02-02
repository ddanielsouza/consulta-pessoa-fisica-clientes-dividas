<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Client extends Model
{
    use \App\Utils\Helpers\ISOSerialization;

    protected $table = "clients";

    protected $fillable = [
        'id',
        'registry',
        'name',
        'hash_registry'
    ];

    protected $hidden = ['registry', 'hash_registry'];
    protected $appends = ['dctRegistry'];

    public function getDctRegistryAttribute()
    {
        return empty($this->registry) ? null : decrypt($this->registry);
    }

    public function setDctRegistryAttribute($value)
    {
        $this->registry = encrypt($value);
        //HASH UNICA PARA EVITAR DUPLICAR DADOS
        $this->hash_registry = hash('md5', $value);
    }

    public function address(){
        return $this->hasMany('App\Models\Address');
    }

    public function debts(){
        return $this->hasMany('App\Models\Debt');
    }
}
