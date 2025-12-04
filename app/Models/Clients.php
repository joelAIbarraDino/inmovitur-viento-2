<?php

namespace App\Models;

use App\Enums\LegalPersonality;
use App\Enums\MaritalPartnership;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $fillable = [
        'no_contract',
        'phone',
        'nacionality',
        'legal_personality',
        'marital_partnership',
        'has_nationalTaxID',
        'has_CURP',
    ];

    protected $guarded = ['id_user'];

    protected $casts = [
        'legal_personality'=>LegalPersonality::class,
        'marital_partnership'=>MaritalPartnership::class
    ];
    
    public function order_payments(){
        return $this->hasMany(OrderPayments::class, 'id_client');
    }

    public function payments(){
        return $this->hasMany(Payments::class, 'id_client');
    }

    public function documents(){
        return $this->hasMany(Documents::class, 'id_client');
    }

    public function condominiums(){
        return $this->hasMany(Condominiums::class, 'id_client');
    }

    public function users(){
        return $this->belongsTo(User::class, 'id_user');
    }
}
