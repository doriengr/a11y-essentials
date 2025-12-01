<?php

namespace App\Http\Controllers;

use Statamic\Facades\Entry;

class PartialRequirementController extends Controller
{
    public function show($slug)
    {
        $requirement = Entry::query()
            ->where('collection', 'requirements')
            ->where('slug', $slug)
            ->first();

        if (! $requirement) {
            return view('partials.requirements.notice');
        }

        $component = Entry::query()
            ->where('collection', 'components')
            ->where('requirements', 'like', "%{$requirement->id()}%")
            ->first();

        return view('partials.requirements.async-load', [
            'requirement' => $requirement,
            'button_url' => $component ? $component->uri() : null,
            'button_label' => $component ? $component->get('title') : null,
        ]);
    }
}
