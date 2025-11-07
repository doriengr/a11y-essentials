<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class AxeRunCommand extends Command
{
    protected $signature = 'axe:run {url}';
    protected $description = 'Runs axe-core check via node + puppeteer';

    public function handle()
    {
        $url = $this->argument('url');

        $this->info("Running AXE on: {$url}");

        $process = new Process(['node', base_path('node/axe-check.js'), $url]);
        $process->setTimeout(60);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error($process->getErrorOutput());
            return Command::FAILURE;
        }

        $output = $process->getOutput();
        $this->line($output);

        return Command::SUCCESS;
    }
}
