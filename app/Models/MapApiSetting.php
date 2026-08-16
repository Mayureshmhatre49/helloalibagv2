<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapApiSetting extends Model
{
    protected $fillable = [
        'enabled',
        'api_key',
        'map_id',
        'monthly_free_limit_map_loads',
        'monthly_free_limit_search',
        'auto_disable_threshold_percent',
        'auto_disabled_at',
        'auto_disabled_reason',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'api_key' => 'encrypted',
        'auto_disabled_at' => 'datetime',
    ];
}
