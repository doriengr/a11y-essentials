<?php

namespace App\Http\Controllers;

use App\Models\EntryUser;
use Illuminate\Http\Request;
use Statamic\Eloquent\Entries\Entry;
use Statamic\Entries\EntryCollection;
use Statamic\View\View;

class ProgressController extends Controller
{
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
                'user_id' => $request->user()->id,
                'entry_id' => $request->input('entry_id'),
            ],
            [
                'collection' => $request->input('collection'),
            ]
        );

        return response()->json(['status' => 'ok']);
    }

    public function show(Request $request)
    {
        $user = $request->user();

        $requirements = $user->visitedEntriesByCollection('requirements');
        $learningModules = $user->visitedEntriesByCollection('learning_modules');
        $components = $this->enrichComponents($requirements, $learningModules);

        $visitedCount = $components->sum(fn ($c) => $c['visited']->count());
        $totalCount = $components->sum(fn ($c) => $c['visited']->count() + $c['not_visited']->count());

        return (new View())
            ->template('templates/progress/show')
            ->layout('layouts.default')
            ->with([
                'title' => 'Dein Lernfortschritt',
                'status' => [
                    'progress_points' => $user->progressPoints(),
                    'points_to_next_level' => $user->pointsToNextLevel(),
                    'level_label' => $user->levelLabel(),
                ],
                'automatic_test_count' => $user->automaticTests()->count(),
                'checklist_count' => $user->checklists()->count(),
                'visited_percentage' => $totalCount ? round(($visitedCount / $totalCount) * 100) : 0,
                'components' => $components,
            ]);
    }

    private function enrichComponents(array $visitedRequirements, array $visitedLearningModules): EntryCollection
    {
        return Entry::query()
            ->whereCollection('components')
            ->get()
            ->map(function ($component) use ($visitedRequirements, $visitedLearningModules) {
                $requirements = $component->requirements ?? collect();
                $learningModules = $component->learning_modules ?? collect();

                $visited = $requirements->merge($learningModules)
                    ->filter(fn ($item) => in_array($item->id, array_merge($visitedRequirements, $visitedLearningModules)));

                $notVisited = $requirements->merge($learningModules)
                    ->filter(fn ($item) => ! in_array($item->id, array_merge($visitedRequirements, $visitedLearningModules)));

                return [
                    'title' => $component->title,
                    'visited' => $visited,
                    'not_visited' => $notVisited,
                ];
            })
            ->sortBy(fn ($c) => strtolower($c['title']))
            ->values();
    }
}
