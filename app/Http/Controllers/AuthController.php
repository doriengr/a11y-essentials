<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            ]);
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
