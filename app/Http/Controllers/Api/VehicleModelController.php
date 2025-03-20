<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVehicleModelRequest;
use App\Http\Requests\UpdateVehicleModelRequest;
use App\Services\VehicleModelService;
use Illuminate\Http\JsonResponse;

class VehicleModelController extends Controller
{
    protected $vehicleModelService;

    public function __construct(VehicleModelService $vehicleModelService)
    {
        $this->vehicleModelService = $vehicleModelService;
    }

    public function index(): JsonResponse
    {
        $models = $this->vehicleModelService->all();
        return response()->json($models);
    }

    public function store(CreateVehicleModelRequest $request): JsonResponse
    {
        $model = $this->vehicleModelService->create($request->validated());
        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        $model = $this->vehicleModelService->findWithYears($id);
        return response()->json($model);
    }

    public function update(UpdateVehicleModelRequest $request, int $id): JsonResponse
    {
        $model = $this->vehicleModelService->update($id, $request->validated());
        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->vehicleModelService->delete($id);
        return response()->json(null, 204);
    }

    public function getByBrandId(int $brandId): JsonResponse
    {
        $models = $this->vehicleModelService->findByBrandId($brandId);
        return response()->json($models);
    }

    public function getByCode(string $code): JsonResponse
    {
        $model = $this->vehicleModelRepository->findByCode($code);
        return response()->json($model);
    }
} 