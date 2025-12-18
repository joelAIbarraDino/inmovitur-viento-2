<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable = [
        'id_client',
        'id_condominium',
        'amount',
        'currency'
    ];

    public function condominiums(){
        return $this->belongsTo(Condominiums::class, 'id_condominium');
    }

    public function clients(){
        return $this->belongsTo(Clients::class, 'id_client');
    }
}
