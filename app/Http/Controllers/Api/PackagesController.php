<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Services\PackageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PackagesController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function index(Request $request): JsonResponse
    {
        $pageNumber = $request->get('page', 1);
        $pageSize = $request->get('per_page', 10);
        
        $packages = $this->packageService->getAllPaginated($pageNumber, $pageSize);
        return response()->json($packages);
    }

    public function store(PackageRequest $request): JsonResponse
    {
        $package = $this->packageService->createPackage($request->validated());
        return response()->json($package, 201);
    }

    public function show(int $id): JsonResponse
    {
        $package = $this->packageService->findWithRelations($id);
        return response()->json($package);
    }

    public function getByCustomerId(int $customerId): JsonResponse
    {
        $packages = $this->packageService->findByCustomerId($customerId);
        return response()->json($packages);
    }

    public function getByVehicleId(int $vehicleId): JsonResponse
    {
        $packages = $this->packageService->findByVehicleId($vehicleId);
        return response()->json($packages);
    }
} 