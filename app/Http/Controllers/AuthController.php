<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // do your creation logic
        // User::create(...)

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
