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
        'company_street',
        'company_city',
        'company_zip',
        'company_country',
        'company_vat',
    ];

}
