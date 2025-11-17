<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;
use Statamic\View\View;

class ChecklistController extends Controller
{
    public function index()
    {
        $checklists = Checklist::where('user_id', auth()->id())->get();

        return (new View())
            ->template('templates/checklists/index')
            ->layout('layouts.default')
            ->with([
                'checklists' => $checklists,
            ]);
    }

    public function create()
    {
        return (new View())
            ->template('templates/checklists/create')
            ->layout('layouts.default');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Checklist::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
        ]);

        return redirect()->route('checklist.index')->with('success', 'Projekt erstellt!');
    }

    public function show(string $id)
    {
        $checklist = Checklist::where('id', $id)->firstOrFail();

        if ($checklist->user_id !== auth()->id()) {
            abort(403);
        }

        return (new View())
            ->template('templates/checklists/show')
            ->layout('layouts.default')
            ->with([
                'checklist' => $checklist,
            ]);
    }

    public function toggle(Request $request, string $checklistId)
    {
        $checklist = Checklist::where('id', $checklistId)->firstOrFail();

        if ($checklist->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'updates' => 'required|array',
            'updates.*' => 'boolean',
        ]);

        $states = $checklist->states ?? [];

        foreach ($validated['updates'] as $key => $value) {
            $states[$key] = $value;
        }

        $checklist->update(['states' => $states]);

        return response()->json(['success' => true]);
    }
}
