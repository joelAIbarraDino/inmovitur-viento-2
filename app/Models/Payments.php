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

    public function clients(){
        return $this->belongsTo(Clients::class, 'id_client');
    }
}
