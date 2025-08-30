<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Hospital Model
 * 
 * @property int $id
 * @property string $name
 * @property string|null $phone_country_code
 * @property string|null $phone
 * @property string $email
 * @property string|null $tax_number
 * @property string $city
 * @property string $country
 * @property string|null $website
 * @property string $address
 * @property string|null $description
 * @property string|null $notes
 * @property string|null $logo_path
 * @property string $status
 * @property int|null $created_by
 * @property string|null $trial_started_at
 * @property string|null $trial_ends_at
 * @property string $subscription_status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Hospital extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Status constants
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_SUSPENDED = 'suspended';

    /**
     * Subscription status constants
     */
    public const SUBSCRIPTION_TRIAL = 'trial';
    public const SUBSCRIPTION_ACTIVE = 'active';
    public const SUBSCRIPTION_EXPIRED = 'expired';
    public const SUBSCRIPTION_CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone_country_code',
        'phone',
        'email',
        'tax_number',
        'city',
        'country',
        'website',
        'address',
        'description',
        'notes',
        'logo_path',
        'status',
        'created_by',
        'trial_started_at',
        'trial_ends_at',
        'subscription_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'trial_started_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    /**
     * Get all users for this hospital
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get admin users for this hospital
     */
    public function admins(): HasMany
    {
        return $this->hasMany(User::class)->where('role', User::ROLE_ADMIN);
    }

    /**
     * Get doctor users for this hospital
     */
    public function doctors(): HasMany
    {
        return $this->hasMany(User::class)->where('role', User::ROLE_DOCTOR);
    }

    /**
     * Get representative users for this hospital
     */
    public function representatives(): HasMany
    {
        return $this->hasMany(User::class)->where('role', User::ROLE_REPRESENTATIVE);
    }

    /**
     * Get patient users for this hospital
     */
    public function patients(): HasMany
    {
        return $this->hasMany(User::class)->where('role', User::ROLE_PATIENT);
    }

    /**
     * Get the user who created this hospital
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get access codes for this hospital
     */
    public function accessCodes(): HasMany
    {
        return $this->hasMany(AccessCode::class);
    }

    /**
     * Get access logs for this hospital
     */
    public function accessLogs(): HasMany
    {
        return $this->hasMany(AccessLog::class);
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by subscription status
     */
    public function scopeSubscriptionStatus($query, string $status)
    {
        return $query->where('subscription_status', $status);
    }

    /**
     * Scope to filter by location
     */
    public function scopeLocation($query, string $city = null, string $country = null)
    {
        if ($city) {
            $query->where('city', 'like', "%{$city}%");
        }
        if ($country) {
            $query->where('country', 'like', "%{$country}%");
        }
        return $query;
    }

    /**
     * Scope to get active hospitals
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope to get trial hospitals
     */
    public function scopeTrial($query)
    {
        return $query->where('subscription_status', self::SUBSCRIPTION_TRIAL);
    }

    /**
     * Scope to get expired hospitals
     */
    public function scopeExpired($query)
    {
        return $query->where('subscription_status', self::SUBSCRIPTION_EXPIRED);
    }

    /**
     * Check if hospital is in trial
     */
    public function isInTrial(): bool
    {
        return $this->subscription_status === self::SUBSCRIPTION_TRIAL;
    }

    /**
     * Check if hospital trial has expired
     */
    public function isTrialExpired(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isPast();
    }

    /**
     * Check if hospital is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if hospital is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    /**
     * Get remaining trial days
     */
    public function getRemainingTrialDays(): int
    {
        if (!$this->trial_ends_at) {
            return 0;
        }

        $remaining = now()->diffInDays($this->trial_ends_at, false);
        return max(0, $remaining);
    }

    /**
     * Start trial period
     */
    public function startTrial(): void
    {
        $this->update([
            'trial_started_at' => now(),
            'trial_ends_at' => now()->addDays(14),
            'subscription_status' => self::SUBSCRIPTION_TRIAL,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Expire trial
     */
    public function expireTrial(): void
    {
        $this->update([
            'subscription_status' => self::SUBSCRIPTION_EXPIRED,
        ]);
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) {
            return null;
        }

        return asset('storage/' . $this->logo_path);
    }
}
