<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\AttendanceReportController;

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Cambiamos el nombre de 'asistencia.index' a 'home'
    Route::get('/', [AttendanceReportController::class, 'index'])->name('home');
    
    // La ruta de búsqueda puede quedarse igual o cambiarla para que sea consistente
    Route::get('/buscar-asistencia', [AttendanceReportController::class, 'buscar'])->name('asistencia.buscar');

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

// Rutas externas (puedes mover la de Welcome aquí si quieres que sea pública)
Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('welcome');

require __DIR__.'/biometric_admin.php';
require __DIR__.'/settings.php';
