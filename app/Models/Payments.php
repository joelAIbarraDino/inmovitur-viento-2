<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable = [
        'id_condominium',
        'amount',
        'discount_condominium',
        'currency',
        'created_at'
    ];

    public function condominiums(){
        return $this->belongsTo(Condominiums::class, 'id_condominium');
    }

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';
}
