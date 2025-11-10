<?php

namespace App\Http\Controllers;

use Statamic\Facades\Entry;

class PartialResourceController extends Controller
{
    public function show($slug)
    {
        $resource = Entry::query()
            ->where('collection', 'resources')
            ->where('slug', $slug)->firstOrFail();

        return view('partials.resource', [
            'resource' => $resource,
        ]);
    }
}
