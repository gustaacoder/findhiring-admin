<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyUser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
