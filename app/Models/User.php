<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use HasApiTokens, HasFactory, Notifiable, \OwenIt\Auditing\Auditable;

    // Never audit these sensitive columns
    protected $auditExclude = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role_id', 'city_id',
        'bio', 'avatar', 'instagram', 'facebook', 'user_website',
        'two_factor_secret', 'two_factor_confirmed_at', 'two_factor_enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function subscription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscription?->isActive() ?? false;
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class, 'created_by');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    // Role helpers
    public function hasRole(string $role): bool
    {
        return $this->role?->slug === $role;
    }

    public function isAdmin(): bool
    {
        return $this->role?->slug === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->role?->slug === 'owner';
    }

    public function isUser(): bool
    {
        return $this->role?->slug === 'user';
    }

    public function blogPosts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    public function getAvatarUrl(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1183d4&color=fff';
    }
}
