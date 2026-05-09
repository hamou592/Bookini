<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ServiceController;
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (protected by role)
Route::middleware(['auth', 'hasRole'])->get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

//(only super_admin)
Route::middleware(['auth', 'hasRole:super_admin'])->group(function () {
    // USERS 
    Route::get('/users', [UserController::class, 'index']);

    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users', [UserController::class, 'store']);

    Route::get('/users/{id}/edit', [UserController::class, 'edit']);
    Route::put('/users/{id}', [UserController::class, 'update']);

    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // PROVIDERS
        Route::get('/providers', [ProviderController::class, 'index']);
        Route::get('/providers/create', [ProviderController::class, 'create']);
        Route::post('/providers', [ProviderController::class, 'store']);

        Route::get('/providers/{id}/edit', [ProviderController::class, 'edit']);
        Route::put('/providers/{id}', [ProviderController::class, 'update']);

        Route::delete('/providers/{id}', [ProviderController::class, 'destroy']);
    
});

Route::middleware(['auth', 'hasRole:super_admin,provider_admin,secretary'])->group(function () {

    Route::get('/departments', [DepartmentController::class, 'index']);

    Route::get('/departments/create', [DepartmentController::class, 'create']);
    Route::post('/departments', [DepartmentController::class, 'store']);

    Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit']);
    Route::put('/departments/{id}', [DepartmentController::class, 'update']);

    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);
});




Route::middleware([
    'auth',
    'hasRole:super_admin,provider_admin,secretary,doctor'
])->group(function () {

    Route::get('/services', [ServiceController::class, 'index']);

    Route::get('/services/create', [ServiceController::class, 'create']);
    Route::post('/services', [ServiceController::class, 'store']);

    Route::get('/services/{id}/edit', [ServiceController::class, 'edit']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);

    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';