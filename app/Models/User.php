<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * User Model
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property int|null $hospital_id
 * @property string|null $phone_country_code
 * @property string|null $phone
 * @property int|null $assigned_doctor_id
 * @property int|null $assigned_representative_id
 * @property string $status
 * @property string|null $last_login_at
 * @property string|null $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Role constants
     */
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_DOCTOR = 'doctor';
    public const ROLE_REPRESENTATIVE = 'representative';
    public const ROLE_PATIENT = 'patient';

    /**
     * Status constants
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_SUSPENDED = 'suspended';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'hospital_id',
        'phone_country_code',
        'phone',
        'assigned_doctor_id',
        'assigned_representative_id',
        'status',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the hospital that this user belongs to
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Get the assigned doctor for this user (if patient)
     */
    public function assignedDoctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_doctor_id');
    }

    /**
     * Get the assigned representative for this user (if patient)
     */
    public function assignedRepresentative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_representative_id');
    }

    /**
     * Get patients assigned to this doctor
     */
    public function assignedPatients(): HasMany
    {
        return $this->hasMany(User::class, 'assigned_doctor_id')->where('role', self::ROLE_PATIENT);
    }

    /**
     * Get patients assigned to this representative
     */
    public function representedPatients(): HasMany
    {
        return $this->hasMany(User::class, 'assigned_representative_id')->where('role', self::ROLE_PATIENT);
    }

    /**
     * Scope to filter by role
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope to filter by hospital
     */
    public function scopeHospital($query, int $hospitalId)
    {
        return $query->where('hospital_id', $hospitalId);
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get staff members (non-patients)
     */
    public function scopeStaff($query)
    {
        return $query->whereIn('role', [self::ROLE_ADMIN, self::ROLE_DOCTOR, self::ROLE_REPRESENTATIVE]);
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is doctor
     */
    public function isDoctor(): bool
    {
        return $this->role === self::ROLE_DOCTOR;
    }

    /**
     * Check if user is representative
     */
    public function isRepresentative(): bool
    {
        return $this->role === self::ROLE_REPRESENTATIVE;
    }

    /**
     * Check if user is patient
     */
    public function isPatient(): bool
    {
        return $this->role === self::ROLE_PATIENT;
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if user is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if user is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    /**
     * Check if user can manage hospital
     */
    public function canManageHospital(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    /**
     * Check if user can view patients
     */
    public function canViewPatients(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin() || $this->isDoctor() || $this->isRepresentative();
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }
}
