<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\AdminSetupController;
use App\Http\Controllers\FixedAssetController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

Route::get('/', function () {
    $company = \App\Models\CompanyInfo::query()->first();
    $adminExists = \App\Models\User::where('is_admin', true)->exists();
    if (!$company) {
        return Inertia::render('Welcome');
    }
    if (!$adminExists) {
        return redirect()->route('admin.setup');
    }
    return redirect()->route('dashboard');
})->name('home');

Route::get('login', function () {
    $company = \App\Models\CompanyInfo::query()->first();
    return Inertia::render('auth/Login', [
        'company' => $company,
    ]);
})->name('login');

Route::post('/company-info', [CompanyInfoController::class, 'store'])->name('company-info.store');
Route::post('/company-info/{id}/details', [CompanyInfoController::class, 'updateDetails'])->name('company-info.updateDetails');
Route::get('/company-info/{id}', [CompanyInfoController::class, 'show'])->name('company-info.show');
Route::match(['get', 'post'], '/admin-setup', [AdminSetupController::class, 'setup'])->name('admin.setup');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/fixed-assets', [FixedAssetController::class, 'index'])->name('fixed-assets.index');
    Route::post('/fixed-assets', [FixedAssetController::class, 'store'])->name('fixed-assets.store');
    Route::put('/fixed-assets/{fixedAsset}', [FixedAssetController::class, 'update'])->name('fixed-assets.update');
    Route::delete('/fixed-assets/{fixedAsset}', [FixedAssetController::class, 'destroy'])->name('fixed-assets.destroy');

    Route::get('/users', function () {
        $users = User::all();
        return Inertia::render('Users', [
            'users' => $users,
        ]);
    })->name('users');
    Route::post('/users', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'is_admin' => 'boolean',
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $validated['is_admin'] ?? false,
        ]);
        return redirect()->back();
    })->name('users.store');
    Route::delete('/users/{id}', function ($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back();
    })->name('users.delete');
});

Route::get('/dashboard', function () {
    $company = \App\Models\CompanyInfo::query()->first();
    $assets = \App\Models\FixedAsset::with('user')->latest()->get();
    return Inertia::render('Dashboard', [
        'company' => $company,
        'assets' => $assets,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
