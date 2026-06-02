<?php

namespace App\Models;

use App\Models\Clinic;
use App\Models\StaffUser;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasUuids;

    /**
     * Audit logs are immutable.
     * The table contains only created_at.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
        'user_id',
        'clinic_id',
        'log_type',
        'entity_type',
        'entity_id',
        'action',
        'original_value',
        'new_value',
        'ip_address',
        'device_info',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'original_value' => 'array',
        'new_value'      => 'array',
        'created_at'     => 'datetime',
    ];

    /**
     * User who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(StaffUser::class);
    }

    /**
     * Clinic associated with the action.
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Tenant associated with the action.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
