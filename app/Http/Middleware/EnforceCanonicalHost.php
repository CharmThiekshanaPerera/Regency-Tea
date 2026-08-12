<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The legacy site ran on plain http:// and lakmatea.com pointed at the Lakma
 * brand archive via .htaccess. Both behaviours are preserved here.
 */
class EnforceCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        // Domain aliases (previously an .htaccess RewriteRule).
        foreach (config('regency.brand_aliases', []) as $alias => $target) {
            if (strcasecmp($host, $alias) === 0) {
                return redirect(rtrim(config('app.url'), '/').$target, 301);
            }
        }

        if (! app()->environment('production')) {
            return $next($request);
        }

        $canonical = parse_url(config('app.url'), PHP_URL_HOST);

        if ($canonical && ($host !== $canonical || ! $request->secure())) {
            return redirect()->to(
                'https://'.$canonical.$request->getRequestUri(), 301
            );
        }

        return $next($request);
    }
}
