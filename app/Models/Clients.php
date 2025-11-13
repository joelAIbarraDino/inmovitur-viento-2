<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $fillable = [
        'phone',
        'nacionality',
        'legal_personality',
        'marital_partnership',
        'has_nationalTaxID',
        'has_CURP',
    ];
                   
}
