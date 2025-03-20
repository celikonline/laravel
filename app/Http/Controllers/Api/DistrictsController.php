<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DistrictService;
use App\Http\Requests\CreateDistrictRequest;
use App\Http\Requests\UpdateDistrictRequest;
use Illuminate\Http\JsonResponse;

class DistrictsController extends Controller
{
    protected $districtService;

    public function __construct(DistrictService $districtService)
    {
        $this->districtService = $districtService;
    }

    public function index(): JsonResponse
    {
        $districts = $this->districtService->all();
        return response()->json($districts);
    }

    public function store(CreateDistrictRequest $request): JsonResponse
    {
        $district = $this->districtService->create($request->validated());
        return response()->json($district, 201);
    }

    public function show(int $id): JsonResponse
    {
        $district = $this->districtService->find($id);
        return response()->json($district);
    }

    public function update(UpdateDistrictRequest $request, int $id): JsonResponse
    {
        $district = $this->districtService->update($id, $request->validated());
        return response()->json($district);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->districtService->delete($id);
        return response()->json(null, 204);
    }

    public function getByCityId(int $cityId): JsonResponse
    {
        $districts = $this->districtService->findByCityId($cityId);
        return response()->json($districts);
    }

    public function getActiveByCityId(int $cityId): JsonResponse
    {
        $districts = $this->districtService->getActiveDistrictsByCityId($cityId);
        return response()->json($districts);
    }
} 