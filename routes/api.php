<?php

use App\Http\Controllers\Master\AgamaController;
use App\Http\Controllers\Master\JenisKelaminController;
use App\Http\Controllers\Master\ProfesiController;
use Illuminate\Support\Facades\Route;

Route::prefix('master')->group(function () {
    Route::prefix('profesi')->group(function () {
        Route::get('/', [ProfesiController::class, 'getProfesi']);
        Route::post('/store', [ProfesiController::class, 'storeProfesi']);
        Route::post('/update', [ProfesiController::class, 'updateProfesi']);
        Route::delete('/delete', [ProfesiController::class, 'deleteProfesi']);
    });
});

Route::prefix('master')->group(function () {
    Route::prefix('agama')->group(function () {
        Route::get('/', [AgamaController::class, 'getAgama']);
        Route::post('/store', [AgamaController::class, 'storeAgama']);
        Route::post('/update', [AgamaController::class, 'updateAgama']);
        Route::delete('/delete', [AgamaController::class, 'deleteAgama']);
    });
});

Route::prefix('master')->group(function () {
    Route::prefix('jeniskelamin')->group(function () {
        Route::get('/', [JenisKelaminController::class, 'getJenisKelamin']);
        Route::post('/store', [JenisKelaminController::class, 'storeJenisKelamin']);
        Route::post('/update', [JenisKelaminController::class, 'updateJenisKelamin']);
        Route::delete('/delete', [JenisKelaminController::class, 'deleteJenisKelamin']);
    });
});

// Route::middleware('auth:sanctum')->group(function () {
//     Route::prefix('master')->group(function () {
//         Route::prefix('profesi')->group(function () {
//             Route::get('/', [ProfesiController::class, 'getProfesi']);
//         });
//     });
// });
