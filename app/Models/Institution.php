<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Institution extends Model
{
    use SoftDeletes;
    use HasUuids;

    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'website',
        'logo',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'status',
        'created_by',
        'updated_by',
    ];
}