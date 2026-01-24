<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function Index()
    {
        return Inertia::render('Users/Index', [
            'users' => User::all(['id', 'name', 'email', 'created_at'])
        ]);
    }

    public function Destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('message', 'Usuario eliminado.');
    }
}