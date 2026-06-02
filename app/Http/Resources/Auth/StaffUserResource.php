<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffUserResource extends JsonResource
{
    /**
     * Transform the StaffUser model into an API response array.
     * Sensitive fields like password_hash are never included.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'first_name'     => $this->first_name,
            'last_name'      => $this->last_name,
            'email'          => $this->email,
            'role'           => $this->role,
            'phone_number'   => $this->phone_number,
            'specialization' => $this->specialization,
            'is_active'      => $this->is_active,
            'home_clinic_id' => $this->home_clinic_id,
            'tenant_id'      => $this->tenant_id,
            'last_login_at'  => $this->last_login_at,
            'created_at'     => $this->created_at,
        ];
    }
}