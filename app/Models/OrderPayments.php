<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayments extends Model
{
    protected $fillable = [
        'id_condominium',
        'amount',
        'discount_condominium',
        'currency',
        'url_spei',
        'clabe',
        'bank_name',
        'order_id',
        'status',
    ];

    public function condominiums(){
        return $this->belongsTo(Condominiums::class, 'id_condominium');
    }
}
