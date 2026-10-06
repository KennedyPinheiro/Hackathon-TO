<?php

namespace App\Services;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public function registrar(
        string $action,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): Audit {
        return Audit::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'auditable_type' => $model?->getMorphClass(),
            'auditable_id' => $model?->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function listar(array $filtros = [])
    {
        return Audit::query()
            ->with('user')
            ->when(
                $filtros['search'] ?? null,
                function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('action', 'like', "%{$search}%")
                            ->orWhere(
                                'auditable_type',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'ip_address',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->when(
                $filtros['action'] ?? null,
                fn($query, $action) =>
                $query->where('action', $action)
            )
            ->when(
                $filtros['user_id'] ?? null,
                fn($query, $userId) =>
                $query->where('user_id', $userId)
            )
            ->when(
                $filtros['auditable_type'] ?? null,
                fn($query, $type) =>
                $query->where('auditable_type', $type)
            )
            ->latest()
            ->paginate(
                $filtros['per_page'] ?? 15
            );
    }

    public function buscar(Audit $audit): Audit
    {
        return $audit->load('user');
    }
}
