<?php

namespace App\Services;

use App\Models\Listing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SearchService
{
    public function search(string $query, array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $builder = Listing::approved()
            ->with(['category', 'area', 'images', 'amenities'])
            ->search($query);

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

        return $builder->latest()->paginate($perPage)->withQueryString();
    }
}
