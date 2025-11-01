<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Statamic\View\View;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        return (new View())
            ->template('templates/auth/registration')
            ->layout('layouts.default')

            ->with([
                'title' => 'Registrierung',
                'success' => session('success'),
                'old' => session()->get('_old_input', []),
            ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:5', 'max:40'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','confirmed','min:8','regex:/^(?=.*[A-Z])(?=.*\d).+$/',],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()
            ->route('auth.registration')
            ->with('success', 'Registrierung erfolgreich!');
    }

    public function login(Request $request)
    {
        return (new View())
            ->template('templates/auth/login')
            ->layout('layouts.default')
            ->with([
                'title' => 'Login',
            ]);
    }
}
