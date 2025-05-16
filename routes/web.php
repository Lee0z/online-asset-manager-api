<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\AdminSetupController;

Route::get('/', function () {
    $companyExists = \App\Models\CompanyInfo::query()->exists();
    $adminExists = \App\Models\User::where('is_admin', true)->exists();
    if (!$companyExists) {
        return Inertia::render('Welcome');
    }
    if (!$adminExists) {
        return redirect()->route('admin.setup');
    }
    return Inertia::render('Dashboard');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/company-info', [CompanyInfoController::class, 'store'])->name('company-info.store');
Route::post('/company-info/{id}/details', [CompanyInfoController::class, 'updateDetails'])->name('company-info.updateDetails');
Route::get('/company-info/{id}', [CompanyInfoController::class, 'show'])->name('company-info.show');
Route::match(['get', 'post'], '/admin-setup', [AdminSetupController::class, 'setup'])->name('admin.setup');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
