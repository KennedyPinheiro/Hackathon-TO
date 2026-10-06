<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexAuditRequest;
use App\Http\Resources\AuditResource;
use App\Models\Audit;
use App\Services\AuditService;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;

class AuditController extends Controller
{
    public function __construct(
        private readonly AuditService $service
    ) {}

    public function index(IndexAuditRequest $request): JsonResponse
    {
        $auditorias = $this->service->listar(
            $request->validated()
        );

        return ResponseService::success(
            data: AuditResource::collection($auditorias)
        );
    }

    public function show(Audit $audit): JsonResponse
    {
        return ResponseService::success(
            data: new AuditResource(
                $this->service->buscar($audit)
            )
        );
    }
}
