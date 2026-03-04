<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'plan', 'status', 'starts_at', 'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    // Plan definitions — add paid plans here later
    public static array $plans = [
        'free' => [
            'name'        => 'Free',
            'slug'        => 'free',
            'price'       => 0,
            'currency'    => '₹',
            'period'      => 'Forever',
            'description' => 'Get started and list your business on Hello Alibaug at no cost.',
            'badge'       => null,
            'cta'         => 'Get Started Free',
            'cta_style'   => 'primary',
            'available'   => true,
            'features'    => [
                '1 listing included',
                'Basic listing page',
                'Customer inquiries via form',
                'Reviews & ratings',
                'Appear in search results',
                'Access to owner dashboard',
            ],
            'unavailable' => [
                'Featured / top placement',
                'Analytics & insights',
                'Priority support',
                'Multiple listings',
            ],
        ],
        'basic' => [
            'name'        => 'Basic',
            'slug'        => 'basic',
            'price'       => 999,
            'currency'    => '₹',
            'period'      => 'per month',
            'description' => 'Perfect for growing businesses that want more visibility.',
            'badge'       => 'Coming Soon',
            'cta'         => 'Coming Soon',
            'cta_style'   => 'disabled',
            'available'   => false,
            'features'    => [
                'Everything in Free',
                'Up to 3 listings',
                'Featured badge on listings',
                'Basic analytics dashboard',
                'Priority email support',
                'WhatsApp inquiry button',
            ],
            'unavailable' => [
                'Top placement in search',
                'Unlimited listings',
            ],
        ],
        'premium' => [
            'name'        => 'Premium',
            'slug'        => 'premium',
            'price'       => 2499,
            'currency'    => '₹',
            'period'      => 'per month',
            'description' => 'For established businesses that want maximum exposure.',
            'badge'       => 'Coming Soon',
            'cta'         => 'Coming Soon',
            'cta_style'   => 'disabled',
            'available'   => false,
            'features'    => [
                'Everything in Basic',
                'Unlimited listings',
                'Top placement in search results',
                'Full analytics & insights',
                'Dedicated account manager',
                'Custom profile branding',
                'Social media promotion',
            ],
            'unavailable' => [],
        ],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isFree(): bool
    {
        return $this->plan === 'free';
    }

    public function getPlanDetails(): array
    {
        return self::$plans[$this->plan] ?? self::$plans['free'];
    }

    /**
     * Activate the free plan immediately.
     */
    public static function activateFree(User $user): self
    {
        return self::updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan'       => 'free',
                'status'     => 'active',
                'starts_at'  => now(),
                'ends_at'    => null,
            ]
        );
    }
}
