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

        $resources = $user->visitedEntriesByCollection('resources');
        $learningModules = $user->visitedEntriesByCollection('learning_modules');
        $components = $this->enrichComponents($resources, $learningModules);

        return (new View())
            ->template('templates/progress/show')
            ->layout('layouts.default')
            ->with([
                'title' => 'Dein Lernprozess',
                'automatic_test_count' => $user->automaticTests()->count(),
                'checklist_count' => $user->checklists()->count(),
                'visited_count' => $components->sum(fn($c) => $c['visited']->count()),
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

    private function enrichComponents(array $visitedResources, array $visitedLearningModules): EntryCollection
    {
        return Entry::query()
            ->whereCollection('components')
            ->get()
            ->map(function ($component) use ($visitedResources, $visitedLearningModules) {
                $resources = $component->resources ?? collect();
                $learningModules = $component->learning_modules ?? collect();

                $visited = $resources->merge($learningModules)
                    ->filter(fn($item) => in_array($item->id, array_merge($visitedResources, $visitedLearningModules)));

                $notVisited = $resources->merge($learningModules)
                    ->filter(fn($item) => !in_array($item->id, array_merge($visitedResources, $visitedLearningModules)));

                return [
                    'title' => $component->title,
                    'visited' => $visited,
                    'not_visited' => $notVisited,
                ];
            })
            ->sortBy(fn($c) => strtolower($c['title']))
            ->values();
    }
}
