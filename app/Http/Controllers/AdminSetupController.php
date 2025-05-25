<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminSetupController extends Controller
{
    public function setup(Request $request)
    {
        if ($request->isMethod('get')) {
            $adminExists = User::where('is_admin', true)->exists();
            if ($adminExists) {
                return redirect('/');
            }
            return Inertia::render('AdminSetup');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => true,
            'is_active' => true,
        ]);
        Auth::login($user);
        return redirect('/');
    }
}
