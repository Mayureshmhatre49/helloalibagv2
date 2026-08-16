<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapApiUsageLog extends Model
{
    public const TYPE_MAP_LOAD = 'map_load';
    public const TYPE_LOCATION_SEARCH = 'location_search';

    protected $fillable = [
        'usage_date',
        'usage_type',
        'hits',
    ];

    protected $casts = [
        'usage_date' => 'date',
    ];
}
