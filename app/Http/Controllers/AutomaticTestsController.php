<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\View\View;

class AutomaticTestsController extends Controller
{
    public function show(Request $request)
    {
        return (new View())
            ->template('templates/tests/show')
            ->layout('layouts.default')

            ->with([
                'title' => 'Überprüfe deinen Code',
            ]);
    }
}
