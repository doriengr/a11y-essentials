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

    public function updateStates(Request $request, string $checklistId)
    {
        $checklist = Checklist::where('id', $checklistId)->firstOrFail();

        if ($checklist->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'states' => 'required|array',
            'states.*' => 'required|array',
            'states.*.*' => 'required|boolean',
        ]);

        $states = $checklist->states ?? [];
        $updatedStates = $validated['states'];

        foreach ($updatedStates as $group => $pairs) {
            foreach ($pairs as $id => $value) {
                if (!isset($states[$group])) {
                    $states[$group] = [];
                }

                $states[$group][$id] = $value;
            }
        }

        $checklist->update(['states' => $states]);

        return response()->json(['success' => true]);
    }

    public function updateGroups(Request $request, string $checklistId)
    {
        $checklist = Checklist::where('id', $checklistId)->firstOrFail();

        if ($checklist->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'groups' => 'required|array',
            'groups.*' => 'required|boolean',
        ]);

        $groups = $checklist->groups ?? [];

        foreach ($validated['groups'] as $group => $value) {
            $groups[$group] = $value;
        }

        $checklist->update([
            'groups' => $groups
        ]);

        return response()->json(['success' => true]);
    }
}
