<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CityService;
use App\Http\Requests\CreateCityRequest;
use App\Http\Requests\UpdateCityRequest;
use Illuminate\Http\JsonResponse;

class CitiesController extends Controller
{
    protected $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    public function index(): JsonResponse
    {
        $cities = $this->cityService->all();
        return response()->json($cities);
    }

    public function store(CreateCityRequest $request): JsonResponse
    {
        $city = $this->cityService->create($request->validated());
        return response()->json($city, 201);
    }

    public function show(int $id): JsonResponse
    {
        $city = $this->cityService->find($id);
        return response()->json($city);
    }

    public function update(UpdateCityRequest $request, int $id): JsonResponse
    {
        $city = $this->cityService->update($id, $request->validated());
        return response()->json($city);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->cityService->delete($id);
        return response()->json(null, 204);
    }

    public function getActive(): JsonResponse
    {
        $cities = $this->cityService->getActiveCities();
        return response()->json($cities);
    }

    public function getByCode(string $code): JsonResponse
    {
        $city = $this->cityService->getCityByCode($code);
        return response()->json($city);
    }
} 