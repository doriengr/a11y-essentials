<?php

namespace App\Http\Controllers;

use App\Models\AutomaticTest;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Statamic\View\View;
use Symfony\Component\Process\Process;

class AutomaticTestController extends Controller
{
    public function show(Request $request)
    {
        $test = null;

        if ($request->has('id')) {
            $test = AutomaticTest::find($request->query('id'));
            if (! $test) {
                return redirect()
                    ->route('test.show')
                    ->with('error', 'Test not found'); // TODO: show error
            }

            return (new View())
                ->template('templates/axe/show')
                ->layout('layouts.default')
                ->with([
                    'title' => 'Deine Testergebnisse',
                    'localized_timestamp' => Carbon::parse($test->results['timestamp'])->timezone('Europe/Berlin'),
                    'results' => $test->results,
                    'input_url' => $test->url,
                    'include_aaa' => $test->include_aaa,
                ]);
        }

        return (new View())
            ->template('templates/axe/show')
            ->layout('layouts.default')
            ->with([
                'title' => 'Überprüfe deinen Code',
            ]);
    }

    public function run(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'include_aaa' => 'nullable',
        ]);

        $url = $validated['url'];

        if (! $url || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['error' => 'Ungültige URL'], 400);
        }

        $includeAAA = $request->boolean('include_aaa');

        $nodePath = trim(shell_exec('which node'));
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

            $test = AutomaticTest::create([
                'url' => $url,
                'include_aaa' => $includeAAA,
                'results' => $results,
            ]);

            return redirect()
                ->route('test.show', ['id' => $test->id]);

        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
