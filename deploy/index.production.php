<?php

/*
 * Production entry point for GoDaddy shared cPanel hosting.
 *
 * GoDaddy locks the web docroot to ~/public_html and won't let it point at
 * the app's /public folder directly, so the app lives one level up in a
 * sibling folder, ~/regency_app. This file is deploy.sh's copy target for
 * ~/public_html/index.php — it is NOT public/index.php and must never be
 * confused with it (that file's ../vendor and ../bootstrap paths are correct
 * for local dev and CI and must stay untouched).
 *
 * Directory layout on the server:
 *   ~/public_html/index.php   <- this file, copied here by deploy.sh
 *   ~/regency_app/vendor
 *   ~/regency_app/bootstrap
 *   ~/regency_app/storage
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
// Storage lives in ../regency_app, not alongside public_html, so this path
// is rewritten from the stock ../storage/... to reach into the app folder.
if (file_exists($maintenance = __DIR__.'/../regency_app/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../regency_app/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__.'/../regency_app/bootstrap/app.php';

$app->handleRequest(Request::capture());
