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
            ->with(['area', 'images', 'amenities', 'tags', 'creator']);

        $this->applyFilters($query, $filters);

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getListingBySlug(string $slug): ?Listing
    {
        return Listing::where('slug', $slug)
            ->approved()
            ->with(['category', 'area', 'images', 'amenities', 'tags', 'listingAttributes', 'creator', 'seoMeta', 'approvedReviews.user', 'approvedReviews.photos'])
            ->first();
    }

    /**
     * Fetch a listing by slug regardless of status — used for admin/owner
     * preview of listings that are still pending or rejected.
     */
    public function getListingBySlugAnyStatus(string $slug): ?Listing
    {
        return Listing::where('slug', $slug)
            ->with(['category', 'area', 'images', 'amenities', 'tags', 'listingAttributes', 'creator', 'seoMeta', 'approvedReviews.user', 'approvedReviews.photos'])
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
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
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
                // Coordinates: use array_key_exists so a deliberately-cleared pin
                // (which arrives as null) actually clears, rather than keeping the old value.
                'latitude' => array_key_exists('latitude', $data) ? $data['latitude'] : $listing->latitude,
                'longitude' => array_key_exists('longitude', $data) ? $data['longitude'] : $listing->longitude,
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

    /**
     * Approve a listing. Uses an atomic conditional update (rather than a
     * plain save) so that if two admins approve the same listing at nearly
     * the same time, only the first one actually applies and sends the
     * approval email/notification — the second is a silent no-op instead of
     * re-sending it.
     */
    public function approve(Listing $listing, User $admin): Listing
    {
        $applied = Listing::where('id', $listing->id)
            ->where('status', '!=', 'approved')
            ->update([
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);

        $listing = $listing->fresh();

        if ($applied === 0) {
            return $listing;
        }

        try {
            Mail::to($listing->creator->email)->send(new ListingApproved($listing));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('ListingApproved mail failed: ' . $e->getMessage());
        }

        \App\Models\UserNotification::create([
            'user_id' => $listing->created_by,
            'type' => 'listing_approved',
            'title' => 'Listing Approved!',
            'message' => '"' . $listing->title . '" has been approved and is now live.',
            'data' => ['listing_id' => $listing->id],
            'action_url' => route('listing.show', [$listing->category->slug, $listing->slug]),
        ]);

        return $listing;
    }

    /**
     * Reject a listing. Same atomic-guard approach as approve() — a listing
     * already rejected won't re-trigger the rejection email/notification.
     */
    public function reject(Listing $listing, ?string $reason = null): Listing
    {
        $applied = Listing::where('id', $listing->id)
            ->where('status', '!=', 'rejected')
            ->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
            ]);

        $listing = $listing->fresh();

        if ($applied === 0) {
            return $listing;
        }

        try {
            Mail::to($listing->creator->email)->send(new ListingRejected($listing));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('ListingRejected mail failed: ' . $e->getMessage());
        }

        \App\Models\UserNotification::create([
            'user_id' => $listing->created_by,
            'type' => 'listing_rejected',
            'title' => 'Listing Needs Changes',
            'message' => '"' . $listing->title . '" was not approved. Reason: ' . $reason,
            'data' => ['listing_id' => $listing->id],
            'action_url' => route('owner.listings.edit', $listing),
        ]);

        return $listing;
    }

    /**
     * Notify every admin — by email AND in-app bell — that a listing needs
     * review, whether it's brand new or an edit that bounced back to
     * pending. Used from every listing-creation/resubmission entry point so
     * none of them can silently skip notifying admins.
     */
    public function notifyAdminsOfSubmission(Listing $listing, bool $isResubmission = false): void
    {
        $admins = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->get();

        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new \App\Mail\ListingSubmitted($listing, true, $isResubmission));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Admin listing email failed: ' . $e->getMessage());
            }

            try {
                \App\Models\UserNotification::create([
                    'user_id' => $admin->id,
                    'type' => $isResubmission ? 'listing_resubmitted' : 'listing_submitted',
                    'title' => $isResubmission ? 'Listing Resubmitted' : 'New Listing Submitted',
                    'message' => $isResubmission
                        ? '"' . $listing->title . '" was edited and resubmitted for approval.'
                        : '"' . $listing->title . '" was submitted and is awaiting approval.',
                    'data' => ['listing_id' => $listing->id],
                    'action_url' => route('admin.listings.index', ['status' => 'pending']),
                ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Admin listing notification failed: ' . $e->getMessage());
            }
        }
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
