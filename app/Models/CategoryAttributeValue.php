<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_attribute_id',
        'value',
        'label',
        'sort_order',
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(CategoryAttribute::class, 'category_attribute_id');
    }
}
