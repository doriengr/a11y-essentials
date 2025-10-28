<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Statamic\View\View;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('user_id', auth()->id())->get();

        return (new View())
            ->template('templates/projects/index')
            ->layout('layouts.default')
            ->with([
                'projects' => $projects,
            ]);
    }

    public function create()
    {
        return (new View())
            ->template('templates/projects/create')
            ->layout('layouts.default');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Project::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
        ]);

        return redirect()->route('projects.index')->with('success', 'Projekt erstellt!');
    }
}
