<?php

use App\Http\Controllers\iclockController;
use Illuminate\Support\Facades\Route;

// Estas rutas NO llevan middleware de autenticación porque las usa el aparato
Route::prefix('iclock')->group(function () {
    Route::get('/cdata', [iclockController::class, 'handshake']);
    Route::post('/cdata', [iclockController::class, 'receiveRecords']);
    Route::get('/getrequest', [iclockController::class, 'getrequest']);
    Route::post('/test', [iclockController::class, 'test']);
});