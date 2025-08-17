<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::as('api.')->prefix('v1')->group(function () {
    # AUTH MODULES
    Route::post('auth/login', [AuthController::class, 'login'])->name('login');
    Route::post('auth/register', [AuthController::class, 'register'])->name('register');
    Route::post('auth/verify', [AuthController::class, 'verify'])->name('verify');
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('auth/check-code', [AuthController::class, 'checkCode'])->name('check-code');
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
    Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');

    Route::middleware('auth:api')->group(function () {
        # PROFILES
        Route::get('profiles', [AuthController::class, 'profile'])->name('profiles');
        Route::post('profiles', [AuthController::class, 'updateProfile'])->name('profiles.update');
        Route::put('profiles/update-password', [AuthController::class, 'updatePassword'])->name('profiles.update-password');
        Route::get('profiles/log-activities', [AuthController::class, 'logActivities'])->name('profiles.log-activities');

        # SETTINGS
        Route::get('settings', [AuthController::class, 'settings'])->name('profiles.settings');

        # USERS
        Route::put('users/update-password/{user}', [UserManagementController::class, 'updatePassword'])->name('users.update-password');
        Route::apiResource('users', UserManagementController::class);

        # ROLES
        Route::get('permissions', [RoleController::class, 'permissions'])->name('permissions');
        Route::apiResource('roles', RoleController::class);
    });
});

Route::get('provinces', [RegionController::class, 'getProvinces'])->name('api.provinces');
Route::get('cities/{provinceId}', [RegionController::class, 'getCities'])->name('api.cities');
Route::get('districts/{cityId}', [RegionController::class, 'getDistricts'])->name('api.districts');
Route::get('villages/{districtId}', [RegionController::class, 'getVillages'])->name('api.villages');
