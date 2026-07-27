<?php

namespace App\Services;

use App\Models\Classified;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Log;

class ClassifiedService
{
    /** How long an active item stays live before auto-expiring. */
    public const LIVE_DAYS = 30;

    public function store(array $data, User $seller): Classified
    {
        $classified = Classified::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? null,
            'is_negotiable' => $data['is_negotiable'] ?? false,
            'condition' => $data['condition'] ?? null,
            'classified_category_id' => $data['classified_category_id'],
            'area_id' => $data['area_id'] ?? null,
            'seller_id' => $seller->id,
            'status' => 'pending',
            'contact_phone' => $data['contact_phone'] ?? null,
            'contact_whatsapp' => $data['contact_whatsapp'] ?? null,
        ]);

        $admins = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->get();
        foreach ($admins as $admin) {
            try {
                UserNotification::create([
                    'user_id' => $admin->id,
                    'type' => 'classified_submitted',
                    'title' => 'New Classified Submitted',
                    'message' => '"' . $classified->title . '" was submitted and is awaiting approval.',
                    'data' => ['classified_id' => $classified->id],
                    'action_url' => route('admin.classifieds.index', ['status' => 'pending']),
                ]);
            } catch (\Throwable $e) {
                Log::error('Admin classified notification failed: ' . $e->getMessage());
            }
        }

        return $classified;
    }

    public function update(Classified $classified, array $data): Classified
    {
        $classified->update([
            'title' => $data['title'] ?? $classified->title,
            'description' => $data['description'] ?? $classified->description,
            'price' => $data['price'] ?? null,
            'is_negotiable' => $data['is_negotiable'] ?? false,
            'condition' => $data['condition'] ?? null,
            'classified_category_id' => $data['classified_category_id'] ?? $classified->classified_category_id,
            'area_id' => $data['area_id'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null,
            'contact_whatsapp' => $data['contact_whatsapp'] ?? null,
        ]);

        return $classified->fresh();
    }

    /**
     * Approve a classified. Atomic conditional update so two admins acting
     * near-simultaneously on the same item can't both fire the notification.
     */
    public function approve(Classified $classified, User $admin): Classified
    {
        $applied = Classified::where('id', $classified->id)
            ->where('status', '!=', 'active')
            ->update([
                'status' => 'active',
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'expires_at' => now()->addDays(self::LIVE_DAYS),
            ]);

        $classified = $classified->fresh();

        if ($applied > 0) {
            $this->notify($classified, 'classified_approved', 'Your item is live!',
                '"' . $classified->title . '" has been approved and is now listed in the marketplace.');
        }

        return $classified;
    }

    public function reject(Classified $classified, ?string $reason = null): Classified
    {
        $applied = Classified::where('id', $classified->id)
            ->where('status', '!=', 'rejected')
            ->update(['status' => 'rejected', 'rejection_reason' => $reason]);

        $classified = $classified->fresh();

        if ($applied > 0) {
            $this->notify($classified, 'classified_rejected', 'Item needs changes',
                '"' . $classified->title . '" was not approved. Reason: ' . $reason);
        }

        return $classified;
    }

    public function markSold(Classified $classified): Classified
    {
        $classified->update(['status' => 'sold', 'sold_at' => now()]);

        return $classified;
    }

    /** Flip active items past their expiry date to 'expired'. Called by the scheduler. */
    public function expireOverdue(): int
    {
        return Classified::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);
    }

    private function notify(Classified $classified, string $type, string $title, string $message): void
    {
        // Approved items are live for buyers to see; anything else sends the
        // seller back to their own edit page (e.g. to fix and resubmit).
        $actionUrl = $type === 'classified_approved'
            ? route('marketplace.show', $classified)
            : route('marketplace.edit', $classified);

        try {
            UserNotification::create([
                'user_id' => $classified->seller_id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => ['classified_id' => $classified->id],
                'action_url' => $actionUrl,
            ]);
        } catch (\Throwable $e) {
            Log::error('Classified notification failed: ' . $e->getMessage());
        }
    }
}
