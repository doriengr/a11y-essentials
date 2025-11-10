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

        if (!$resource) {
            return view('partials.resources.notice');
        }

        return view('partials.resources.disclosure', [
            'resource' => $resource,
        ]);
    }
}
