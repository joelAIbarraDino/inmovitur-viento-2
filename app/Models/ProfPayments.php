<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfPayments extends Model
{
    protected $fillable = [
        'id_condominium',
        'original_name',
        'stored_name',
        'path',
        'amount',
        'discount_condominium',
        'currency',
        'status'
    ];

    public function condominiums(){
        return $this->belongsTo(Condominiums::class, 'id_condominium');
    }
}
