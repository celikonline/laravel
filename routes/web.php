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

    // routes/web.php
Route::get('/packages/{package}/contract/pdf', [PackageController::class, 'generateContractPdf'])->name('packages.contract.pdf');
Route::get('/packages/{package}/receipt-pdf', [PackageController::class, 'downloadReceiptPdf'])->name('packages.receipt.pdf');
Route::get('/packages/export/csv', [PackageController::class, 'exportCsv'])->name('packages.export.csv');
Route::get('/packages/export/excel', [PackageController::class, 'exportExcel'])->name('packages.export.excel');
Route::get('/packages/export/pdf', [PackageController::class, 'exportPdf'])->name('packages.export.pdf');
Route::get('/packages/{package}/contract-preview', [PackageController::class, 'contractPreview'])->name('packages.contract-preview');
Route::get('/packages/{package}/receipt-preview', [PackageController::class, 'receiptPreview'])->name('packages.receipt-preview');
Route::get('/packages/download-agreement', [PackageController::class, 'downloadAgreementPdf'])->name('packages.download-agreement');
Route::get('/packages/download-kvkk', [PackageController::class, 'downloadKvkkPdf'])->name('packages.download-kvkk');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
