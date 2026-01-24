<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function Index()
    {
        return Inertia::render('Employees/MapId', [
            'employees' => Employee::orderBy('employee_id', 'asc')->get()
        ]);
    }

    public function Store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'name' => 'required|string|max:255'
        ]);

        Employee::updateOrCreate(
            ['employee_id' => $request->employee_id],
            ['name' => $request->name]
        );

        // En Inertia, regresamos un "back" para que Vue refresque los datos
        return redirect()->back()->with('message', 'Empleado vinculado con éxito.');
    }
}