<?php

use App\Http\Controllers\Authentication\AuthenticationController;
use App\Http\Controllers\Master\AgamaController;
use App\Http\Controllers\Master\JenisKelaminController;
use App\Http\Controllers\Master\JenisMediaController;
use App\Http\Controllers\Master\ModuleController;
use App\Http\Controllers\Master\PegawaiController;
use App\Http\Controllers\Master\ProfesiController;
use App\Http\Controllers\Master\UnitController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\UserPermission;
use Illuminate\Support\Facades\Route;

// Login
Route::middleware('guest')->group(function () {
    Route::post('/login', [
        AuthenticationController::class,
        'login'
    ]);
});

Route::middleware('auth:sanctum')->group(function () {

    // Data user yang sedang login
    Route::get('/me', [AuthenticationController::class, 'me']);

    Route::prefix('master')->group(function () {
        Route::prefix('profesi')->group(function () {
            Route::get('/', [ProfesiController::class, 'getProfesi']);
            Route::post('/store', [ProfesiController::class, 'storeProfesi']);
            Route::post('/update', [ProfesiController::class, 'updateProfesi']);
            Route::delete('/delete', [ProfesiController::class, 'deleteProfesi']);
        });

        Route::prefix('agama')->group(function () {
            Route::get('/', [AgamaController::class, 'getAgama']);
            Route::post('/store', [AgamaController::class, 'storeAgama']);
            Route::post('/update', [AgamaController::class, 'updateAgama']);
            Route::delete('/delete', [AgamaController::class, 'deleteAgama']);
        });

        Route::prefix('jeniskelamin')->group(function () {
            Route::get('/', [JenisKelaminController::class, 'getJenisKelamin']);
            Route::post('/store', [JenisKelaminController::class, 'storeJenisKelamin']);
            Route::post('/update', [JenisKelaminController::class, 'updateJenisKelamin']);
            Route::delete('/delete', [JenisKelaminController::class, 'deleteJenisKelamin']);
        });

        Route::prefix('jenismedia')->group(function () {
            Route::get('/', [JenisMediaController::class, 'getJenisMedia']);
            Route::post('/store', [JenisMediaController::class, 'storeJenisMedia']);
            Route::post('/update', [JenisMediaController::class, 'updateJenisMedia']);
            Route::delete('/delete', [JenisMediaController::class, 'deleteJenisMedia']);
        });

        Route::prefix('unit')->group(function () {
            Route::get('/', [UnitController::class, 'getUnit']);
            Route::post('/store', [UnitController::class, 'storeUnit']);
            Route::post('/update', [UnitController::class, 'updateUnit']);
            Route::delete('/delete', [UnitController::class, 'deleteUnit']);
        });

        Route::prefix('modules')->group(function () {
            Route::get('/', [ModuleController::class, 'getModules']);
            Route::post('/store', [ModuleController::class, 'storeModule']);
            Route::post('/update', [ModuleController::class, 'updateModule']);
            Route::delete('/delete', [ModuleController::class, 'deleteModule']);
        });

        Route::prefix('user-permissions')->group(function () {
            Route::get('/{userId}', [UserPermission::class, 'getPermissions']);
            Route::post('/store', [UserPermission::class, 'storePermissions']);
            Route::get(
                '/{userId}',
                [UserPermission::class, 'getPermissionsByUserId']
            );
        });
    });

    Route::prefix('management-user')->group(function () {
        Route::prefix('pegawai')->group(function () {
            Route::get('/', [PegawaiController::class, 'getPegawai']);
            Route::post('/store', [PegawaiController::class, 'storePegawai']);
            Route::post('/update', [PegawaiController::class, 'updatePegawai']);
            Route::delete('/delete', [PegawaiController::class, 'deletePegawai']);
        });

        Route::prefix('pengguna')->group(function () {
            Route::get('/', [UserController::class, 'getUsers']);
            Route::post('/store', [UserController::class, 'createUser']);
            Route::post('/update', [UserController::class, 'updateUser']);
            Route::delete('/delete', [UserController::class, 'deleteUser']);
        });
    });

    // Logout
    Route::post('/logout', [AuthenticationController::class, 'logout']);
});
