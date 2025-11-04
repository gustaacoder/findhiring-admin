<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Companies extends Model
{
    use softDeletes;

    protected $fillable = [
        'name',
        'cnpj',
        'address',
        'email',
        'phone_number',
        'logo_url',
        'number_employees',
        'description',
    ];
}
