<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    protected $fillable = [
        'id_client',
        'original_name',
        'stored_name',
        'type_document',
        'path'
    ];
}
