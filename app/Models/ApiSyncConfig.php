<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiSyncConfig extends Model
{
    protected $fillable = [
        'name',
        'url',
        'method',
        'auth_type',
        'auth_credentials',
        'headers',
        'mappings',
        'is_active',
    ];

    protected $casts = [
        'auth_credentials' => 'array',
        'headers' => 'array',
        'mappings' => 'array',
        'is_active' => 'boolean',
    ];
}
