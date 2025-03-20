<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateServicePackageRequest;
use App\Http\Requests\UpdateServicePackageRequest;
use App\Services\ServicePackageService;
use Illuminate\Http\JsonResponse;

class ServicePackageController extends Controller
{
    protected $servicePackageService;

    public function __construct(ServicePackageService $servicePackageService)
    {
        $this->servicePackageService = $servicePackageService;
    }

    public function index(): JsonResponse
    {
        $servicePackages = $this->servicePackageService->all();
        return response()->json($servicePackages);
    }

    public function store(CreateServicePackageRequest $request): JsonResponse
    {
        $servicePackage = $this->servicePackageService->create($request->validated());
        return response()->json($servicePackage, 201);
    }

    public function show(int $id): JsonResponse
    {
        $servicePackage = $this->servicePackageService->findWithServices($id);
        return response()->json($servicePackage);
    }

    public function update(UpdateServicePackageRequest $request, int $id): JsonResponse
    {
        $servicePackage = $this->servicePackageService->update($id, $request->validated());
        return response()->json($servicePackage);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->servicePackageService->delete($id);
        return response()->json(null, 204);
    }
} 