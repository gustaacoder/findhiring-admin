<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'requirements',
        'responsibilities',
        'location',
        'salary',
        'start_date',
        'end_date',
    ];
}
