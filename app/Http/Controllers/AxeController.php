<?php

namespace App\Http\Controllers;

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
        ]);

        $url = $validated['url'];

        $url = $request->input('url');
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['error' => 'Ungültige URL'], 400);
        }

        $nodePath = trim(shell_exec('which node'));
        $process = new Process([$nodePath, base_path('node/axe-check.js'), $url]);
        $process->setTimeout(60);

        try {
            $process->run();
            if (!$process->isSuccessful()) {
                throw new \Exception($process->getErrorOutput());
            }
            $results = json_decode($process->getOutput(), true);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
