<?php

namespace App\Services;

use App\Models\Listing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SearchService
{
    public function search(string $query = '', array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $builder = Listing::approved()
            ->with(['category', 'area', 'images', 'amenities']);

        if (!empty($query)) {
            $builder->search($query);
        }

        if (!empty($filters['category_id'])) {
            $builder->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['area_id'])) {
            $builder->where('area_id', $filters['area_id']);
        }

        if (!empty($filters['min_price'])) {
            $builder->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $builder->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['amenities']) && is_array($filters['amenities'])) {
            $builder->whereHas('amenities', function ($q) use ($filters) {
                $q->whereIn('amenities.id', $filters['amenities']);
            });
        }

        // Sort
        $sort = $filters['sort'] ?? 'newest';
        match ($sort) {
            'price_asc' => $builder->orderBy('price', 'asc'),
            'price_desc' => $builder->orderBy('price', 'desc'),
            'popular' => $builder->orderBy('views_count', 'desc'),
            default => $builder->latest(),
        };

        return $builder->paginate($perPage)->withQueryString();
    }
}
