<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Every page here is editable through the admin panel and must reflect a
 * save immediately, on a normal refresh, with no incognito tab needed.
 *
 * Explicit `no-store` rather than the framework's default `no-cache,
 * private` — `no-cache` still permits a cache to store the response and
 * revalidate it later, which depends on every intermediary (proxy, ISP
 * cache, browser) correctly honouring a conditional request. `no-store`
 * forbids storing the response at all, so there is nothing to serve stale
 * regardless of how a downstream cache behaves.
 *
 * Scoped to HTML only — static assets (images, /build/*) are served
 * directly by Apache and cache aggressively via .htaccess, which is
 * correct since their filenames are content-addressed (ULID / hashed
 * build output) and never change under the same URL.
 */
class PreventPageCaching
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (str_starts_with($response->headers->get('Content-Type', ''), 'text/html')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
