<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Scopes\TenantScope;

/**
 * Clinic Model
 * 
 * Represents a physical clinic location belonging to a tenant organization.
 * Each clinic is one of potentially many locations under one tenant.
 * 
 * @property string $id
 * @property string $tenant_id
 * @property string $name
 * @property string $city
 * @property string $address
 * @property string $phone_number
 * @property string $email
 * @property string $operating_hours_start
 * @property string $operating_hours_end
 * @property integer $min_receptionists_per_shift
 * @property integer $min_nurses_per_shift
 * @property integer $min_doctors_per_shift
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */


class Clinic extends Model
{

    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'name',
        'city',
        'address',
        'phone_number',
        'email',
        'operating_hours_start',
        'operating_hours_end',
        'min_receptionists_per_shift',
        'min_nurses_per_shift',
        'min_doctors_per_shift',
        'status'
    ];

    /**
     * Boot the model and apply global scopes.
     * TenantScope ensures every query is automatically filtered by tenant_id.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());
    }

    /**
     * Get the tenant that owns this clinic.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
