<?php

namespace App\Models;

use App\Enums\AdminTypes;
use Illuminate\Database\Eloquent\Model;

class Admins extends Model
{
    protected $fillable = [
        'type_admin',
        'review_docs',
    ];

    public function users(){
        return $this->belongsTo(User::class, 'id_user');
    }
}
