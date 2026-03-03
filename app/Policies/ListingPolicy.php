<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;

class ListingPolicy
{
    public function update(User $user, Listing $listing): bool
    {
        return $user->id === $listing->created_by || $user->isAdmin();
    }

    public function delete(User $user, Listing $listing): bool
    {
        return $user->id === $listing->created_by || $user->isAdmin();
    }

    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }

    public function reject(User $user): bool
    {
        return $user->isAdmin();
    }
}
