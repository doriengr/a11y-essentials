<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\View\View;

class ProgressController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return (new View())
            ->template('templates/progress/show')
            ->layout('layouts.default')
            ->with([
                'title' => 'Dein Lernprozess',
                'automatic_test_count' => $user->automaticTests()->count(),
                'checklist_count' => $user->checklists()->count(),
            ]);
    }
}
