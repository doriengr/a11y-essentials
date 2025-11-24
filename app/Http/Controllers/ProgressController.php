<?php

namespace App\Http\Controllers;

use App\Models\EntryUser;
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

    public function store(Request $request)
    {
        if (! $request->user()) {
            return response()->json(['status' => 'guest'], 200);
        }

        $request->validate([
            'entry_id' => 'required|string',
            'collection' => 'required|string',
        ]);

        $user = $request->user();
        $entryId = $request->input('entry_id');
        $collection = $request->input('collection');

        EntryUser::create(
            ['user_id' => $user->id,
            'entry_id' => $entryId,
            'collection' => $collection
        ]);

        return response()->json(['status' => 'ok']);
    }
}
