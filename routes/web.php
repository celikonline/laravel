<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VehicleBrandController;
use App\Http\Controllers\VehicleModelController;

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
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');

    // Paket işlemleri
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{id}/edit', [PackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{id}', [PackageController::class, 'update'])->name('packages.update');
    Route::get('/packages/{id}/payment', [PackageController::class, 'payment'])->name('packages.payment');
    Route::post('/packages/{id}/payment', [PackageController::class, 'processPayment'])->name('packages.process-payment');
    Route::get('/packages/proposals', [PackageController::class, 'proposals'])->name('packages.proposals');
    
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
Route::get('/packages/all', [PackageController::class, 'allPackages'])->name('packages.all');

    // Raporlama
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('/revenue', [App\Http\Controllers\ReportController::class, 'revenue'])->name('revenue');
        Route::get('/packages', [App\Http\Controllers\ReportController::class, 'packages'])->name('packages');
        Route::get('/customers', [App\Http\Controllers\ReportController::class, 'customers'])->name('customers');
    });

    // Audit Log Routes
    Route::get('/audit-logs', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit.index');
    Route::get('/audit-logs/{log}', [App\Http\Controllers\AuditLogController::class, 'show'])->name('audit.show');

    // Profile Routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bildirim rotaları
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');

    // Vehicle Settings Routes
    Route::resource('vehicle-brands', VehicleBrandController::class);
    Route::resource('vehicle-models', VehicleModelController::class);
    Route::get('vehicle-models/by-brand/{brand}', [VehicleModelController::class, 'getModelsByBrand'])->name('vehicle-models.by-brand');
});

Auth::routes();
