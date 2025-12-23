<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayments extends Model
{
    protected $fillable = [
        'id_client',
        'amount',
        'discount_condominium',
        'currency',
        'url_spei',
        'clabe',
        'bank_name',
        'order_id',
        'status',
    ];

    public function clients(){
        return $this->belongsTo(Clients::class, 'id_client');
    }
}
