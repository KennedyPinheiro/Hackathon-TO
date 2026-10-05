<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,

            'roles' => $this->whenLoaded(
                'roles',
                fn() => $this->roles->pluck('name')->values()
            ),

            'permissions' => $this->whenLoaded(
                'permissions',
                fn() => $this->permissions->pluck('name')->values()
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
