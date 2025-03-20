<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVehicleModelYearRequest;
use App\Http\Requests\UpdateVehicleModelYearRequest;
use App\Services\VehicleModelYearService;
use Illuminate\Http\JsonResponse;

class VehicleModelYearController extends Controller
{
    protected $vehicleModelYearService;

    public function __construct(VehicleModelYearService $vehicleModelYearService)
    {
        $this->vehicleModelYearService = $vehicleModelYearService;
    }

    public function index(): JsonResponse
    {
        $years = $this->vehicleModelYearService->all();
        return response()->json($years);
    }

    public function store(CreateVehicleModelYearRequest $request): JsonResponse
    {
        $year = $this->vehicleModelYearService->create($request->validated());
        return response()->json($year, 201);
    }

    public function show(int $id): JsonResponse
    {
        $year = $this->vehicleModelYearService->find($id);
        return response()->json($year);
    }

    public function update(UpdateVehicleModelYearRequest $request, int $id): JsonResponse
    {
        $year = $this->vehicleModelYearService->update($id, $request->validated());
        return response()->json($year);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->vehicleModelYearService->delete($id);
        return response()->json(null, 204);
    }

    public function getByModelId(int $modelId): JsonResponse
    {
        $years = $this->vehicleModelYearService->findByModelId($modelId);
        return response()->json($years);
    }
} 