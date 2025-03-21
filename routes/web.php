<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Paket işlemleri
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{id}/edit', [PackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{id}', [PackageController::class, 'update'])->name('packages.update');
    Route::get('/packages/{id}/payment', [PackageController::class, 'payment'])->name('packages.payment');
    Route::post('/packages/{id}/payment', [PackageController::class, 'processPayment'])->name('packages.process-payment');
    
    // AJAX istekleri
    Route::get('/packages/vehicle-models/{brand_id}', [PackageController::class, 'getVehicleModels']);
    Route::get('/packages/districts/{city_id}', [PackageController::class, 'getDistricts']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
