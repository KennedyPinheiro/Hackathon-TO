<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'action' => $this->action,

            'user' => $this->whenLoaded(
                'user',
                fn() => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ]
            ),

            'auditable' => [
                'type' => $this->auditableType(),
                'label' => $this->auditableLabel(),
                'id' => $this->auditable_id,
            ],

            'old_values' => $this->old_values,
            'new_values' => $this->new_values,

            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function auditableType(): ?string
    {
        if (!$this->auditable_type) {
            return null;
        }

        return match ($this->auditable_type) {
            User::class => 'user',
            default => class_basename($this->auditable_type),
        };
    }

    private function auditableLabel(): ?string
    {
        if (!$this->auditable_type) {
            return null;
        }

        return match ($this->auditable_type) {
            User::class => 'Usuário',
            default => class_basename($this->auditable_type),
        };
    }
}
