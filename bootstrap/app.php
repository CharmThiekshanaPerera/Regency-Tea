<?php

use App\Http\Middleware\EnforceCanonicalHost;
use App\Http\Middleware\HandleLegacyRedirects;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Legacy WordPress URLs are resolved only after a 404, so normal
        // traffic pays nothing. See discovery/url-map.csv (666 rows).
        $middleware->prependToGroup('web', EnforceCanonicalHost::class);
        // Must run after Laravel's own StartSession (appendToGroup, not
        // prependToGroup — prepends land before StartSession in the group
        // array, and $request->session() isn't available yet at that point).
        $middleware->appendToGroup('web', SetLocale::class);
        $middleware->appendToGroup('web', HandleLegacyRedirects::class);

        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
