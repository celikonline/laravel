<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CitiesController;
use App\Http\Controllers\Api\DistrictsController;
use App\Http\Controllers\Api\PackageTypesController;
use App\Http\Controllers\Api\PackagesController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\PackageTypeController;
use App\Http\Controllers\Api\ServicePackageController;
use App\Http\Controllers\Api\VehicleBrandController;
use App\Http\Controllers\Api\VehicleModelController;
use App\Http\Controllers\Api\VehicleModelYearController;
use App\Http\Controllers\Api\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->middleware('auth:sanctum');
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Cities routes
    Route::get('cities/active', [CitiesController::class, 'getActive']);
    Route::get('cities/code/{code}', [CitiesController::class, 'getByCode']);
    Route::apiResource('cities', CitiesController::class);

    // Districts routes
    Route::get('districts/city/{cityId}', [DistrictsController::class, 'getByCityId']);
    Route::get('districts/city/{cityId}/active', [DistrictsController::class, 'getActiveByCityId']);
    Route::apiResource('districts', DistrictsController::class);

    // Package Types routes
    Route::get('package-types', [PackageTypesController::class, 'index']);

    // Packages routes
    Route::get('packages/customer/{customerId}', [PackagesController::class, 'getByCustomerId']);
    Route::get('packages/vehicle/{vehicleId}', [PackagesController::class, 'getByVehicleId']);
    Route::apiResource('packages', PackagesController::class)->only(['index', 'store']);

    // Cities
    Route::get('/cities/active', [CityController::class, 'getActiveCities']);
    Route::get('/cities/code/{code}', [CityController::class, 'getCityByCode']);

    // Districts
    Route::get('/districts/city/{cityId}', [\App\Http\Controllers\Api\DistrictController::class, 'getDistrictsByCity']);

    // Package Types
    Route::get('/package-types', [PackageTypeController::class, 'index']);

    // Service Packages
    Route::apiResource('service-packages', ServicePackageController::class);

    // Vehicle Brands
    Route::get('/vehicle-brands/code/{code}', [VehicleBrandController::class, 'getByCode']);
    Route::apiResource('vehicle-brands', VehicleBrandController::class);

    // Vehicle Models
    Route::get('/vehicle-models/brand/{brandId}', [VehicleModelController::class, 'getByBrandId']);
    Route::get('/vehicle-models/code/{code}', [VehicleModelController::class, 'getByCode']);
    Route::apiResource('vehicle-models', VehicleModelController::class);

    // Vehicle Model Years
    Route::get('/vehicle-model-years/model/{modelId}', [VehicleModelYearController::class, 'getByModelId']);
    Route::apiResource('vehicle-model-years', VehicleModelYearController::class);

    // Packages
    Route::get('/packages/customer/{customerId}', [PackageController::class, 'getPackagesByCustomer']);
    Route::get('/packages/vehicle/{vehicleId}', [PackageController::class, 'getPackagesByVehicle']);
    Route::apiResource('packages', PackageController::class);

    // New route
    Route::get('/districts', [CustomerController::class, 'getDistricts']);
});
