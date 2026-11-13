<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LoginAttempt extends Model
{
    use HasUuids;

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'attempted_email',
        'matched_user_id',
        'ip_address',
        'device_info',
        'success',
        'failure_reason',
        'attempted_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'success' => 'boolean',
        'attempted_at' => 'datetime',
    ];
}