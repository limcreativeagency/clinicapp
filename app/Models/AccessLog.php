<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * AccessLog Model
 * 
 * @property int $id
 * @property int|null $access_code_id
 * @property int $hospital_id
 * @property int|null $user_id
 * @property string $action
 * @property string $status
 * @property string|null $details
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AccessLog extends Model
{
    use HasFactory;

    /**
     * Action constants
     */
    public const ACTION_REQUEST = 'request';
    public const ACTION_VERIFY = 'verify';
    public const ACTION_ACCESS = 'access';
    public const ACTION_EXPIRE = 'expire';

    /**
     * Status constants
     */
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'access_code_id',
        'hospital_id',
        'user_id',
        'action',
        'status',
        'details',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Get the access code for this log
     */
    public function accessCode(): BelongsTo
    {
        return $this->belongsTo(AccessCode::class);
    }

    /**
     * Get the hospital for this log
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Get the user for this log
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by action
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by hospital
     */
    public function scopeHospital($query, int $hospitalId)
    {
        return $query->where('hospital_id', $hospitalId);
    }

    /**
     * Scope to filter by user
     */
    public function scopeUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Log an access request
     */
    public static function logRequest(int $hospitalId, int $userId, string $ip = null, string $userAgent = null, array $details = []): self
    {
        return self::create([
            'hospital_id' => $hospitalId,
            'user_id' => $userId,
            'action' => self::ACTION_REQUEST,
            'status' => self::STATUS_SUCCESS,
            'details' => $details,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Log a code verification
     */
    public static function logVerification(int $hospitalId, int $userId, int $accessCodeId, bool $success, string $ip = null, string $userAgent = null, array $details = []): self
    {
        return self::create([
            'access_code_id' => $accessCodeId,
            'hospital_id' => $hospitalId,
            'user_id' => $userId,
            'action' => self::ACTION_VERIFY,
            'status' => $success ? self::STATUS_SUCCESS : self::STATUS_FAILED,
            'details' => $details,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Log data access
     */
    public static function logAccess(int $hospitalId, int $userId, int $accessCodeId, string $ip = null, string $userAgent = null, array $details = []): self
    {
        return self::create([
            'access_code_id' => $accessCodeId,
            'hospital_id' => $hospitalId,
            'user_id' => $userId,
            'action' => self::ACTION_ACCESS,
            'status' => self::STATUS_SUCCESS,
            'details' => $details,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Log code expiration
     */
    public static function logExpiration(int $hospitalId, int $accessCodeId, array $details = []): self
    {
        return self::create([
            'access_code_id' => $accessCodeId,
            'hospital_id' => $hospitalId,
            'action' => self::ACTION_EXPIRE,
            'status' => self::STATUS_SUCCESS,
            'details' => $details,
        ]);
    }
}
