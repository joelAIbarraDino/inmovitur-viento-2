<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condominiums extends Model
{
    protected $fillable = [
        'id_client',
        'tower',
        'number',
        'price',
        'currency'
    ];

    public function payments(){
        return $this->hasMany(Payments::class, 'id_condominium');
    }

    public function clients(){
        return $this->belongsTo(Clients::class, 'id_client');
    }
}
