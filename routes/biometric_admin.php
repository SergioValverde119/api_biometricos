<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Solo pedimos que el usuario esté logueado ('auth')
Route::middleware(['auth'])->group(function () {
    
    // 1. Reportes de Asistencia
    Route::get('attendance', [DeviceController::class, 'Attendance'])->name('devices.Attendance');
    Route::get('attendance/data', [DeviceController::class, 'getAttendance'])->name('devices.getAttendance');
    Route::get('daily', [DeviceController::class, 'daily'])->name('devices.daily');
    Route::get('daily/data', [DeviceController::class, 'getDailyAttendanceSummary'])->name('devices.getDailyAttendanceSummary');
    Route::get('monthly', [DeviceController::class, 'monthly'])->name('devices.monthly');
    Route::get('monthly/data', [DeviceController::class, 'getMonthlyAttendanceSummary'])->name('devices.getMonthlyAttendanceSummary');

    // 2. Gestión de Dispositivos y Logs
    Route::get('devices', [DeviceController::class, 'Index'])->name('devices.index');
    Route::get('devices-log', [DeviceController::class, 'DeviceLog'])->name('devices.DeviceLog');
    Route::get('finger-log', [DeviceController::class, 'FingerLog'])->name('devices.FingerLog');
    
    // 3. Vinculación de Empleados (Map ID)
    Route::get('map_id', [EmployeeController::class, 'Index'])->name('employee.MapId');
    Route::post('map_id', [EmployeeController::class, 'Store'])->name('employee.store');
    
    // 4. Gestión de Usuarios
    Route::get('users_biometric', [UserController::class, 'Index'])->name('users_biometric.index');
    Route::post('users_biometric', [UserController::class, 'Store'])->name('users_biometric.store');
    Route::delete('users_biometric/{id}', [UserController::class, 'Destroy'])->name('users_biometric.destroy');
});