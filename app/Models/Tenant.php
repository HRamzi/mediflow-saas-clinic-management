<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tenant Model
 * 
 * Represents a clinic chain organization on the MediFlow platform.
 * Each tenant is a completely separate business entity with full data isolation.
 * 
 * @property string $id
 * @property string $name
 * @property string $owner_name
 * @property string $email
 * @property string $phone
 * @property string $country
 * @property string $status
 * @property string $subscription_plan
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Tenant extends Model
{

    use HasUuids;

    protected $fillable = [
        'name',
        'owner_name',
        'email',
        'phone',
        'country',
        'status',
        'subscription_plan'
    ];

    /**
     * Get all clinics belonging to this tenant.
     * A tenant can have multiple clinic locations.
     */
    public function clinics(): HasMany
    {
        return $this->hasMany(Clinic::class);
    }
}