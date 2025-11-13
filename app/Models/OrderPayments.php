<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayments extends Model
{
    protected $fillable = [
        'id_client',
        'amount',
        'currency',
        'provider_id',
        'customer_reference',
        'clabe',
        'bank_name'
    ];
}
