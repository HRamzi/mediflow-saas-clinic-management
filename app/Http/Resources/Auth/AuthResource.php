<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Auth\StaffUserResource;
use App\Models\StaffUser;


class AuthResource extends JsonResource
{
    private string $token;

    public function __construct(StaffUser $user, string $token)
    {
        parent::__construct($user);
        $this->token = $token;
    }

    public function toArray(Request $request): array
    {
        return [
            'token' => $this->token,
            'user'  => new StaffUserResource($this->resource),
        ];
    }
}
