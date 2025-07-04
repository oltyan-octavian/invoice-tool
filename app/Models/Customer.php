<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'street',
        'city',
        'zip',
        'country',
        'email',
        'phone_number',
        'is_legal_entity',
        'company_name',
        'company_vat',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }


}
