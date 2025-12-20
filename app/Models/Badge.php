<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $table = "badge";
    
    protected $fillable = [
        'currency_origin',
        'currency_target',
        'rate',
    ];
}
