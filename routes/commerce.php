<?php

/*
|--------------------------------------------------------------------------
| Phase C — commerce routes
|--------------------------------------------------------------------------
| Loaded from routes/web.php only when config('regency.commerce_enabled')
| is true. The legacy WordPress export contains no pricing, stock or weight
| data, so these stay dormant until a price list has been imported.
|
| See discovery/PHASE2-MIGRATION-PLAN.md §0 and §7 (phase C).
*/

use Illuminate\Support\Facades\Route;

Route::get('/cart', fn () => abort(501, 'Commerce not yet enabled'))->name('cart');
Route::get('/checkout', fn () => abort(501, 'Commerce not yet enabled'))->name('checkout');
