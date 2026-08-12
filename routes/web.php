<?php

use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes  (PHASE2-MIGRATION-PLAN.md §4)
|--------------------------------------------------------------------------
| Slugs are preserved from the legacy site wherever possible so that the
| redirect surface stays as small as it can be. Anything that does move is
| covered by the `redirects` table via the HandleLegacyRedirects middleware.
*/

Route::get('/', [PageController::class, 'home'])->name('home');

// ---- static content pages -------------------------------------------------
Route::get('/about',           [PageController::class, 'show'])->defaults('slug', 'about')->name('about');
Route::get('/ceylon-tea',      [PageController::class, 'show'])->defaults('slug', 'ceylon-tea')->name('ceylon-tea');
Route::get('/health-benefits', [PageController::class, 'show'])->defaults('slug', 'health-benefits')->name('health-benefits');
Route::get('/faqs',            [PageController::class, 'show'])->defaults('slug', 'faqs')->name('faqs');
Route::get('/team',            [PageController::class, 'show'])->defaults('slug', 'team')->name('team');
Route::get('/private-label',   [PageController::class, 'show'])->defaults('slug', 'private-label')->name('private-label');
Route::get('/capabilities',    [PageController::class, 'show'])->defaults('slug', 'capabilities')->name('capabilities');
Route::get('/catalogue',       [PageController::class, 'catalogue'])->name('catalogue');

// ---- catalogue ------------------------------------------------------------
Route::get('/product-ranges',              [CatalogueController::class, 'ranges'])->name('ranges');
Route::get('/product-ranges/{category}',   [CatalogueController::class, 'range'])->name('range.show');
Route::get('/brands/{brand}',              [CatalogueController::class, 'brand'])->name('brand.show');
Route::get('/products',                    [CatalogueController::class, 'index'])->name('products');
Route::get('/products/{product}',          [CatalogueController::class, 'show'])->name('product.show');

// ---- media centre ---------------------------------------------------------
Route::get('/media',                        [MediaController::class, 'index'])->name('media');
Route::get('/media/category/{postCategory}', [MediaController::class, 'category'])->name('media.category');
Route::get('/media/{post}',                 [MediaController::class, 'show'])->name('media.show');

// ---- contact & enquiries --------------------------------------------------
Route::get('/contact',  [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('contact.store');

Route::get('/search', [CatalogueController::class, 'search'])->name('search');

/*
|--------------------------------------------------------------------------
| Phase C — commerce. Enabled only once a price list has been imported.
|--------------------------------------------------------------------------
*/
if (config('regency.commerce_enabled')) {
    require __DIR__.'/commerce.php';
}
