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
}
