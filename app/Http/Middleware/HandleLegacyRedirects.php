<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serves the 666 legacy WordPress URLs captured in discovery/url-map.csv.
 * Only runs on 404, so it costs nothing on normal traffic.
 */
class HandleLegacyRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->getStatusCode() !== 404) {
            return $response;
        }

        $path = '/'.trim($request->getPathInfo(), '/');

        $redirect = Cache::remember(
            "redirect:{$path}",
            now()->addDay(),
            fn () => Redirect::where('from_path', $path)->first()
        );

        if (! $redirect) {
            return $response;
        }

        Redirect::whereKey($redirect->id)->update([
            'hits'        => $redirect->hits + 1,
            'last_hit_at' => now(),
        ]);

        if ($redirect->status_code === 410) {
            abort(410);
        }

        return redirect($redirect->to_path, $redirect->status_code);
    }
}
