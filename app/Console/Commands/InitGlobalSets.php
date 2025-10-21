<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str as LStr;
use Statamic\Facades\Blueprint;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;
use Statamic\Facades\Stache;
use Statamic\Support\Str;

class InitGlobalSets extends Command
{
    protected $signature = 'app:init-globals';

    protected $description = 'Initialise global set files, if they don’t exist yet.';

    public function handle(): int
    {
        $locales = Site::all()->pluck('handle');
        $globalSetDir = Str::ensureRight(Stache::store('globals')->directory(), '/');
        $initialisedSets = [];

        if (file_exists($globalSetDir) && ! is_dir($globalSetDir)) {
            $this->info('The globals folder could not be created, because a file of that name already exists.');

            return Command::FAILURE;
        }

        if (! file_exists($globalSetDir)) {
            mkdir($globalSetDir);
        }

        $locales->each(function ($locale) use ($globalSetDir) {
            $localeDir = "{$globalSetDir}{$locale}/";

            // Create subdirectories for locales.
            if (! file_exists($localeDir)) {
                mkdir($localeDir);
            }
        });

        foreach (Blueprint::in('globals')->keys() as $blueprintHandle) {
            if (GlobalSet::findByHandle($blueprintHandle)) {
                continue;
            }

            file_put_contents($globalSetDir . $blueprintHandle . '.yaml', 'title: ' . LStr::headline($blueprintHandle));

            // Create global files for locales.
            $locales->each(function ($locale) use ($globalSetDir, $blueprintHandle) {
                $fileContents = $locale === 'de' ? '{  }' : 'origin: de';

                file_put_contents("{$globalSetDir}{$locale}/{$blueprintHandle}.yaml", $fileContents);
            });

            $initialisedSets[] = $blueprintHandle;
        }

        if (count($initialisedSets)) {
            $this->info('The following globals needed to be initialised: ' . implode(', ', $initialisedSets));
        } else {
            $this->info('No globals needed to be initialised.');
        }

        return Command::SUCCESS;
    }
}
