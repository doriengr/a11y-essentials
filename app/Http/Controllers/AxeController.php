<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Statamic\View\View;
use Symfony\Component\Process\Process;

class AxeController extends Controller
{
    public function show(Request $request)
    {
        return (new View())
            ->template('templates/axe/show')
            ->layout('layouts.default')
            ->with([
                'title' => 'Überprüfe deinen Code',
                'success' => session('success'),
                'old' => session()->get('_old_input', []),
            ]);
    }

    public function run(Request $request)
    {
        $validated = $request->validate([
            'url' => ['required', 'url'],
            'include_aaa' => ['nullable'],
        ]);

        $url = $validated['url'];

        if (! $url || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['error' => 'Ungültige URL'], 400);
        }

        // boolean: checkbox checked or not
        $includeAAA = $request->boolean('include_aaa');

        $nodePath = trim(shell_exec('which node'));

        // prepare node arguments
        $args = [$nodePath, base_path('node/axe-checker.js'), $url];

        if ($includeAAA) {
            $args[] = 'aaa';
        }

        $process = new Process($args);
        $process->setTimeout(60);

        try {
            $process->run();

            if (! $process->isSuccessful()) {
                throw new Exception($process->getErrorOutput());
            }

            $results = json_decode($process->getOutput(), true);

            return (new View())
                ->template('templates/axe/show')
                ->layout('layouts.default')
                ->with([
                    'title' => 'Deine Testergebnisse',
                    'localized_timestamp' => Carbon::parse($results['timestamp'])->timezone('Europe/Berlin'),
                    'results' => $results,
                    'input_url' => $url,
                    'input_include_aaa' => $includeAAA,
                ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
