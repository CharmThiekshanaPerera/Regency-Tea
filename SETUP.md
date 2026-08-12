# Regency Teas — Laravel 11 rebuild

Full replacement for the legacy WordPress + WooCommerce + Elementor site, built from the read-only backup in `../_backup`.

**131 PHP files · 26 tables · 32 Blade views · 12 admin resources · 23 tests.**

---

## What you need to run first

This directory holds the **application source**. It does not include `vendor/`, `node_modules/`, or the Laravel framework skeleton — those come from Composer and npm, which could not run in the environment where this was generated (no PHP available, Packagist network-blocked).

### Prerequisites

PHP 8.2+ (`pdo_mysql`, `pdo_sqlite`, `mbstring`, `gd`, `zip`) · Composer 2 · MySQL 8 / MariaDB 10.6+ · Node 20+

### 1. Install

```bash
cd "D:\Phyxle Web Projects\Regency Teas\regency-laravel"

composer install
npm install

cp .env.example .env
php artisan key:generate
```

```sql
CREATE DATABASE regency_teas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Set `DB_*` in `.env`.

> If Composer reports a missing `artisan` or `public/index.php`, run `composer create-project laravel/laravel:^11.0 tmp` in a scratch folder and copy across `artisan`, `public/`, `bootstrap/providers.php`, the stock `config/*.php` files (keep our `config/regency.php`), `resources/css`, `resources/js`, `vite.config.js`. The files here overlay on top.

### 2. Migrate, import content, import images

```bash
php artisan migrate
php artisan db:seed
php artisan wp:import          # content: 434 SKUs -> ~329 products
php artisan wp:media           # images: 1,599 originals -> public/media
php artisan media:verify       # proves every image reference resolves
php artisan sitemap:generate
```

> No `storage:link` needed. Images are served as plain files from `public/media`,
> which avoids the symlink that commonly breaks on shared/cPanel hosting.
> **Deploying to a server? See [DEPLOY-MEDIA.md](DEPLOY-MEDIA.md).**

`wp:import` reads `database/wp-migration.sqlite` — the original **377 MB** dump distilled to **1.7 MB** of pure content. `wp:media` reads `../discovery/media-files.csv` and copies only referenced originals from the read-only backup.

Both commands are **idempotent**. Useful flags:

```bash
php artisan wp:import --only=products
php artisan wp:import --fresh
php artisan wp:media --dry-run
php artisan wp:media --all       # include the 7,750 orphans too
```

### 3. Run

```bash
npm run dev          # terminal 1
php artisan serve    # terminal 2
```

Front end at `http://localhost:8000`, admin at `/admin`
(`admin@regencyteas.com` / the value of `ADMIN_PASSWORD`, default `change-me-now` — **change it**).

---

## Expected import result

| Table | Rows |
|---|---|
| brands | 4 (Hyleys 347, Lakma 82, Truly Ceylon 16, Dr. Tea 10) |
| product_groups | 9 |
| categories | 67 (72 legacy − 5 merged/dropped) |
| products | ~329 |
| product_variants | 434 |
| attributes / values | 4 / ~120 |
| posts / post_categories | 69 / 4 |
| pages | 8 |
| redirects | ~600 |
| media files | 1,599 originals (802 MB) |

`wp:import` deliberately warns that **0 of 434 variants have a price**. That is correct — see "Why there are no prices" below.

---

## Routes

```
/                          Home (hero slider, brands, ranges, new arrivals, news)
/about  /ceylon-tea  /health-benefits  /faqs  /team  /capabilities
/private-label             Private label service
/catalogue                 PDF catalogue downloads
/product-ranges            All 72 categories, grouped into 9 sections
/product-ranges/{category} Category archive with facets
/brands/{brand}            Brand archive
/products                  Full catalogue, faceted + sortable
/products/{slug}           Product detail: gallery, pack sizes, item codes, specs, tabs
/media                     Media Centre (69 posts)
/media/category/{slug}
/media/{slug}
/search                    Product + item-code search
/contact                   Enquiry form
/admin                     Filament admin
/sitemap.xml  /robots.txt
```

Legacy URLs are served by `HandleLegacyRedirects`, which runs **only on a 404** so normal traffic costs nothing. Junk pages return **410 Gone**, not 301.

---

## Admin panel

| Group | Resources |
|---|---|
| **Catalogue** | Products (with pack-size repeater), Categories, Brands, Range groups, Attributes |
| **Content** | Pages, Media Centre, News categories |
| **Site** | Navigation, Enquiries (with unread badge), Redirects (with hit counts), Homepage slider |

Dashboard shows product and variant counts, enquiries awaiting reply, **products missing an image**, and the latest enquiries.

---

## Why there are no prices

Reading `_backup/wp-content/themes/teapoz-child/style.css` revealed this:

```css
.woocommerce_options_panel fieldset.form-field,
.woocommerce_options_panel p.form-field { display: none !important; }
#woocommerce-product-data .woocommerce_options_panel { display: none; }
```

Someone **deliberately hid the WooCommerce pricing and inventory panel** from wp-admin, and `functions.php` relabels "SKU" to **"Item Code"**. This ran as a **B2B export catalogue**, not a shop — which matches the database exactly: 434 products, every price `0.0000`, and zero orders in the store's lifetime.

The build reflects that. Product pages show item codes and pack specifications with a **"Request a quote"** call to action. Enquiries land in the admin inbox with product context attached.

Commerce is not removed, just dormant: `price_cents`, `stock_qty`, `weight_g` and `tax_class` exist as nullable columns on `product_variants`, the Filament pricing fieldset appears when enabled, and `routes/commerce.php` loads only when `COMMERCE_ENABLED=true`. **Switching on a real shop is a data import, not a rebuild.**

---

## Design decisions

**Brand is a foreign key, not a taxonomy.** Every product has exactly one brand. WordPress modelled it many-to-many, which made `/product-brand/hyleys/` a second-class archive.

**Pack sizes are variants.** `"ENGLISH ARISTOCRATIC - 100 TEA BAGS"` and its 11 siblings become one product with 12 variants. `PackSizeParser` normalises inconsistent base names (`ENGLISH ARISTOCRATIC TEA` → `ENGLISH ARISTOCRATIC`) so they group correctly. 434 legacy SKUs → ~329 products.

**`New Arrivals` is not a category.** It was a 69-product category in WordPress; here it's the `Product::newArrivals()` scope over `published_at`.

**Elementor is gone.** 44.5 MB of layout JSON was replaced by Blade components. The page *copy* was extracted to `../discovery/pages/*.md`; the layouts were rebuilt.

**Features deliberately not built.** `_extra_info`, `_video_select`, `_sizechart_select`, `_product_360_image_gallery` and `bought_together` all exist in the theme but are populated on **zero** products. Same for 11 of the 24 Elementor widgets and product reviews (`woocommerce_enable_reviews = no`).

---

## Testing

```bash
php artisan test
```

23 tests: `PackSizeParser` against real legacy titles, every public route, contact validation and honeypot, redirect resolution and hit counting, and confirmation that commerce routes stay disabled.

---

## Still outstanding

These are **content and decision items, not code**:

1. **Category and brand imagery does not exist.** Every legacy `thumbnail_id` is `0` and the one brand-logo row is empty. `/product-ranges` and the brand strip will look bare until images are supplied. Admin upload fields are ready.
2. **Private Label and Our Capabilities have almost no copy.** Private Label was 4 KB of images with zero extractable text. The templates include a sensible default structure; the words need writing.
3. **Homepage slider is empty.** The legacy Slider Revolution slides live in `wp10_revslider_slides` as plugin-specific JSON. Add slides via `/admin` → Homepage slider.
4. **Confirm before launch:** canonical domain, `lakmatea.com` DNS (its redirect to `/brands/lakma` is preserved in `EnforceCanonicalHost`), whether the `auto-translate` locale was ever live, and whether the 53 NextGEN galleries hold anything current.
5. **Decide on commerce** — see "Why there are no prices".

---

*`_backup/` is read-only and verified unmodified by MD5 throughout the build.*
