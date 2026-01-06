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
        'monthly_payment',
        'currency'
    ];

    public function payments(){
        return $this->hasMany(Payments::class, 'id_condominium');
    }

    public function clients(){
        return $this->belongsTo(Clients::class, 'id_client');
    }

    public function orderPayments(){
        return $this->hasMany(OrderPayments::class, 'id_condominium');
    }

    public function profPayments(){
        return $this->hasMany(ProfPayments::class, 'id_condominium');
    }
}
