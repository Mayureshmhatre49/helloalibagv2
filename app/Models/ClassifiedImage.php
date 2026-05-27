<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassifiedImage extends Model
{
    protected $fillable = ['classified_id', 'path', 'alt_text', 'sort_order', 'is_primary'];

    protected $casts = ['is_primary' => 'boolean'];

    public function classified(): BelongsTo
    {
        return $this->belongsTo(Classified::class);
    }

    public function getUrlAttribute(): string
    {
        return str_starts_with($this->path, 'http')
            ? $this->path
            : asset('storage/' . $this->path);
    }
}
