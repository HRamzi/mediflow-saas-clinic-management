<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Scopes\TenantScope;


class StaffUser extends Authenticatable
{
    use HasUuids, HasApiTokens, HasFactory, Notifiable;

    protected $hidden = ['password_hash', 'remember_token'];

    protected $fillable = [
        'tenant_id',
        'home_clinic_id',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password_hash',
        'role',
        'specialization',
        'is_active',
        'revoked_at',
        'revoked_by_user_id',
        'last_login_at'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'revoked_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the password for authentication.
     * MediFlow uses password_hash instead of the default password column.
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    /**
     * Get the tenant that owns this staff user.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the clinic that owns this staff user.
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'home_clinic_id');
    }
}
