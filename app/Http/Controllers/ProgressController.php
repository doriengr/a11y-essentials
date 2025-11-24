<?php

namespace App\Http\Controllers;

use App\Models\EntryUser;
use Illuminate\Http\Request;
use Statamic\Eloquent\Entries\Entry;
use Statamic\Entries\EntryCollection;
use Statamic\View\View;

class ProgressController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $visited_resources = $user->visitedEntriesByCollection('resources');
        $components = $this->enrichComponents($visited_resources);

        return (new View())
            ->template('templates/progress/show')
            ->layout('layouts.default')
            ->with([
                'title' => 'Dein Lernprozess',
                'automatic_test_count' => $user->automaticTests()->count(),
                'checklist_count' => $user->checklists()->count(),
                'visited_resources_count' => $components->sum(fn($c) => $c['visited_resources']->count()),
                'components' => $components,
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

        EntryUser::firstOrCreate(
            [
                'user_id'  => $request->user()->id,
                'entry_id' => $request->input('entry_id'),
            ],
            [
                'collection'       => $request->input('collection'),
            ]
        );

        return response()->json(['status' => 'ok']);
    }

    private function enrichComponents(array $visitedResources): EntryCollection
    {
        $components = Entry::query()
            ->whereCollection('components')
            ->get();

        $components = $components->map(function ($component) use ($visitedResources) {
            $resources = $component->resources ?? collect();

            return [
                'title' => $component->title,
                'visited_resources' => $resources->filter(fn($r) => in_array($r->id, $visitedResources)),
                'not_visited_resources' => $resources->filter(fn($r) => !in_array($r->id, $visitedResources)),
            ];
        });

        return $components->sortBy(fn($c) => strtolower($c['title']))->values();
    }
}
