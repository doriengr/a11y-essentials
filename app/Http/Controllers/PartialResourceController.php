<?php

namespace App\Http\Controllers;

use Statamic\Facades\Entry;

class PartialResourceController extends Controller
{
    public function show($slug)
    {
        $resource = Entry::query()
            ->where('collection', 'resources')
            ->where('slug', $slug)
            ->first();

        if (! $resource) {
            return view('partials.resources.notice');
        }

        $component = Entry::query()
            ->where('collection', 'components')
            ->where('resources', 'like', "%{$resource->id()}%")
            ->first();

        return view('partials.resources.async-load', [
            'resource' => $resource,
            'button_url' => $component ? $component->uri() : null,
            'button_label' => $component ? $component->get('title') : null,
        ]);
    }
}
