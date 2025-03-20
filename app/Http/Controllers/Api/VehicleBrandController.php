<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVehicleBrandRequest;
use App\Http\Requests\UpdateVehicleBrandRequest;
use App\Services\VehicleBrandService;
use Illuminate\Http\JsonResponse;

class VehicleBrandController extends Controller
{
    protected $vehicleBrandService;

    public function __construct(VehicleBrandService $vehicleBrandService)
    {
        $this->vehicleBrandService = $vehicleBrandService;
    }

    public function index(): JsonResponse
    {
        $brands = $this->vehicleBrandService->all();
        return response()->json($brands);
    }

    public function store(CreateVehicleBrandRequest $request): JsonResponse
    {
        $brand = $this->vehicleBrandService->create($request->validated());
        return response()->json($brand, 201);
    }

    public function show(int $id): JsonResponse
    {
        $brand = $this->vehicleBrandService->findWithModels($id);
        return response()->json($brand);
    }

    public function update(UpdateVehicleBrandRequest $request, int $id): JsonResponse
    {
        $brand = $this->vehicleBrandService->update($id, $request->validated());
        return response()->json($brand);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->vehicleBrandService->delete($id);
        return response()->json(null, 204);
    }

    public function getByCode(string $code): JsonResponse
    {
        $brand = $this->vehicleBrandService->findByCode($code);
        return response()->json($brand);
    }
} 