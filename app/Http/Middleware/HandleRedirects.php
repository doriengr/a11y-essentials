<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Statamic\Entries\Entry;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;
use Statamic\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if ($response->getStatusCode() !== 404) {
            return $response;
        }

        $urlToMatch = "/{$request->path()}";
        $redirects = $this->fetchRedirectsFromGlobalSet();

        if (! $redirects) {
            return null;
        }

        if ($directMatch = $redirects->firstWhere('url_old', $urlToMatch)) {
            return redirect($this->resolveEntryUrl($directMatch)['url'], $directMatch['response']);
        }

        $wildcardRedirect = $redirects
            ->filter(fn ($redirect) => $this->isWildcard($redirect['url_old']))
            ->filter(fn ($redirect) => $this->isMatchingWildcard($redirect['url_old'], $urlToMatch))
            ->first();

        if ($wildcardRedirect) {
            return redirect($this->resolveEntryUrl($wildcardRedirect)['url'], $wildcardRedirect['response']);
        }

        return $response;
    }

    protected function resolveEntryUrl(array $directMatch): array
    {
        if ($directMatch['url_type'] === 'external') {
            return array_merge($directMatch, [
                'url' => $directMatch['url_external'],
            ]);
        }

        return array_merge($directMatch, [
            'url' => Entry::find($directMatch['entry'])->url() ?? '/',
        ]);
    }

    protected function fetchRedirectsFromGlobalSet(): Collection
    {
        $redirects = collect();

        foreach (Site::all()->keys()->toArray() as $site) {
            $set = GlobalSet::findByHandle('redirects');

            if (! $set) {
                continue;
            }

            $set = $set
                ->in($site)
                ->data()
                ->get('redirects');

            collect($set)->each(fn ($redirect) => $redirects->push($redirect))->unique('url_old');
        }

        return $redirects;
    }

    protected function isWildcard(string $url): bool
    {
        return Str::contains($url, '*');
    }

    protected function isMatchingWildcard(string $url, string $urlToMatch): bool
    {
        $wildcardRoute = Str::before($url, '*');

        return Str::startsWith($urlToMatch, $wildcardRoute);
    }
}
