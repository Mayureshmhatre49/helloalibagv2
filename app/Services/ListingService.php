<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingAttribute;
use App\Models\ListingImage;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ListingApproved;
use App\Mail\ListingRejected;

class ListingService
{
    public function getApprovedListings(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Listing::approved()
            ->with(['category', 'area', 'images', 'amenities', 'creator']);

        $this->applyFilters($query, $filters);

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getFeaturedListings(int $limit = 8)
    {
        return Listing::approved()
            ->featured()
            ->with(['category', 'area', 'images', 'amenities'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getListingsByCategory(Category $category, array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Listing::approved()
            ->where('category_id', $category->id)
            ->with(['area', 'images', 'amenities', 'creator']);

        $this->applyFilters($query, $filters);

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getListingBySlug(string $slug): ?Listing
    {
        return Listing::where('slug', $slug)
            ->approved()
            ->with(['category', 'area', 'images', 'amenities', 'creator', 'approvedReviews.user'])
            ->first();
    }

    public function store(array $data, User $user): Listing
    {
        return DB::transaction(function () use ($data, $user) {
            $listing = Listing::create([
                'title' => $data['title'],
                'category_id' => $data['category_id'],
                'area_id' => $data['area_id'] ?? null,
                'description' => $data['description'] ?? null,
                'price' => $data['price'] ?? null,
                'status' => 'pending',
                'created_by' => $user->id,
                'address' => $data['address'] ?? null,
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'website' => $data['website'] ?? null,
                'whatsapp' => $data['whatsapp'] ?? null,
            ]);

            // Dynamic attributes
            if (!empty($data['attributes'])) {
                foreach ($data['attributes'] as $key => $value) {
                    if ($value !== null && $value !== '') {
                        ListingAttribute::create([
                            'listing_id' => $listing->id,
                            'attribute_key' => $key,
                            'attribute_value' => $value,
                        ]);
                    }
                }
            }

            // Amenities
            if (!empty($data['amenities'])) {
                $listing->amenities()->sync($data['amenities']);
            }

            return $listing;
        });
    }

    public function update(Listing $listing, array $data): Listing
    {
        return DB::transaction(function () use ($listing, $data) {
            $listing->update([
                'title' => $data['title'] ?? $listing->title,
                'category_id' => $data['category_id'] ?? $listing->category_id,
                'area_id' => $data['area_id'] ?? $listing->area_id,
                'description' => $data['description'] ?? $listing->description,
                'price' => $data['price'] ?? $listing->price,
                'address' => $data['address'] ?? $listing->address,
                'phone' => $data['phone'] ?? $listing->phone,
                'email' => $data['email'] ?? $listing->email,
                'website' => $data['website'] ?? $listing->website,
                'whatsapp' => $data['whatsapp'] ?? $listing->whatsapp,
            ]);

            // Update dynamic attributes
            if (!empty($data['attributes'])) {
                foreach ($data['attributes'] as $key => $value) {
                    $listing->setDynamicAttribute($key, $value);
                }
            }

            // Update amenities
            if (isset($data['amenities'])) {
                $listing->amenities()->sync($data['amenities']);
            }

            return $listing->fresh();
        });
    }

    public function approve(Listing $listing, User $admin): Listing
    {
        $listing->update([
            'status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);
        
        Mail::to($listing->creator->email)->send(new ListingApproved($listing));
        
        \App\Models\UserNotification::create([
            'user_id' => $listing->created_by,
            'type' => 'listing_approved',
            'title' => 'Listing Approved!',
            'message' => '"' . $listing->title . '" has been approved and is now live.',
            'data' => ['listing_id' => $listing->id],
        ]);
        
        return $listing;
    }

    public function reject(Listing $listing, ?string $reason = null): Listing
    {
        $listing->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);
        
        Mail::to($listing->creator->email)->send(new ListingRejected($listing));
        
        \App\Models\UserNotification::create([
            'user_id' => $listing->created_by,
            'type' => 'listing_rejected',
            'title' => 'Listing Needs Changes',
            'message' => '"' . $listing->title . '" was not approved. Reason: ' . $reason,
            'data' => ['listing_id' => $listing->id],
        ]);
        
        return $listing;
    }

    public function getUserListings(User $user, ?string $status = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Listing::where('created_by', $user->id)
            ->with(['category', 'area', 'images']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['area_id'])) {
            $query->where('area_id', $filters['area_id']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['amenities'])) {
            $query->whereHas('amenities', function ($q) use ($filters) {
                $q->whereIn('amenities.id', $filters['amenities']);
            });
        }

        if (!empty($filters['sort'])) {
            match ($filters['sort']) {
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'rating' => $query->orderBy('views_count', 'desc'),
                'newest' => $query->latest(),
                default => $query->latest(),
            };
        }
    }
}
