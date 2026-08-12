<?php

namespace App\Console\Commands;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\ProductVariant;
use App\Models\Redirect;
use App\Support\PackSizeParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDO;

/**
 * Imports the legacy WordPress catalogue from the slim SQLite export produced
 * during Phase 1 discovery (database/wp-migration.sqlite, 1.7 MB).
 *
 * Idempotent: every step matches on the wp_* id columns and updates in place,
 * so the command can be re-run safely.
 *
 *   php artisan wp:import              # everything
 *   php artisan wp:import --only=products
 *   php artisan wp:import --fresh      # wipe imported tables first
 */
class ImportWordPress extends Command
{
    protected $signature = 'wp:import
                            {--only= : brands|groups|categories|attributes|products|posts|pages|menus|redirects}
                            {--fresh : truncate imported tables before running}';

    protected $description = 'Import legacy WordPress content from the discovery SQLite export';

    private PDO $wp;

    /** Grouping of the 72 legacy product categories. See PHASE2-MIGRATION-PLAN.md §3. */
    private const GROUPS = [
        'wellness-functional' => ['Wellness & Functional', [
            'colon-clense', 'slim-tea', 'super-slim', 'super-cleanse', 'garcinia-cambogia',
            'garcinia-with-green-tea', '14-days-detox-kit', '28-days-detox-kit', 'dieters-tea',
            'diabetic-tea', 'immune-support', 'gut-health', 'sleep-tea', 'calm-tea',
            'beauty-tea', 'womens-tea', 'wellness-tea', 'wellness-tea-collection',
            'wellness-green-tea', 'moringa-tea', 'moringa-oleifera', 'turmeric', 'ginseng-tea',
        ]],
        'black-teas' => ['Black Teas', [
            'ceylon-black-tea', 'english-breakfast-tea', 'english-aristoctatic-tea',
            'earl-grey-tea', 'english-royal-blend', 'english-royal', 'english-favourite-tea',
            'scottish-breakfast-tea', 'premium-ceylon', 'single-region', 'royal-tea',
            'everyday-tea',
        ]],
        'green-matcha' => ['Green & Matcha', [
            'english-green-tea', 'matcha', 'organic-tea',
        ]],
        'herbal-infusions' => ['Herbal & Infusions', [
            'tea-infusions', 'butterfly-pea-flower', 'apema-kahata', 'tea-harmony',
        ]],
        'fruit-flavoured' => ['Fruit & Flavoured', [
            'fruity-tea-collection', 'passion-fruit-black-tea', 'passion-fruit-green-tea',
            'strawberry-with-cream', 'coconut-tea', 'guava-tea', 'black-tea-ginger',
            'tea-with-additives',
        ]],
        'collections-gifting' => ['Collections & Gifting', [
            'exquisite-collection', 'natures-harmony-supreme', 'premium-tea-collection',
            'travellers-collection', 'tea-moments', 'tea-moktails', 'elephant-range',
            'maroon-range', 'botanical-range', 'ceylon-gold',
        ]],
        'bulk-foodservice' => ['Bulk & Foodservice', [
            'bulk-tea', 'horeca', 'premium-loose-tea', 'tea-standards', 'smart-price',
        ]],
        // 'dual-brew' exists only as a pa_tea-menu attribute term, not a category.
        'iced-rtd' => ['Iced & Ready to Drink', ['iced_tea']],
        'kids' => ['Kids', ['kids-tea']],
    ];

    /** Legacy categories that duplicate a brand or are otherwise dropped. */
    private const DROP_CATEGORIES = [
        'lakma-earl-grey-tea'            => 'earl-grey-tea',
        'lakma-english-aristocratic-tea' => 'english-aristoctatic-tea',
        'lakma-wellness-tea-collection'  => 'wellness-tea-collection',
        'new-arrivals'                   => null,  // becomes a dynamic listing
        'uncategorized'                  => null,
        'tea'                            => null,
    ];

    private const ATTRIBUTE_LABELS = [
        'pa_tea-menu'          => 'Tea Type',
        'pa_collection'        => 'Collection',
        'pa_benefits'          => 'Benefits',
        'pa_packaging-options' => 'Packaging',
    ];

    public function handle(): int
    {
        $path = database_path('wp-migration.sqlite');

        if (! is_file($path)) {
            $this->error("Missing {$path}. Run discovery/_tmp/make_slim_db.py first.");

            return self::FAILURE;
        }

        $this->wp = new PDO('sqlite:'.$path);
        $this->wp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->wp->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        if ($this->option('fresh')) {
            $this->fresh();
        }

        $only  = $this->option('only');
        $steps = [
            'brands'     => fn () => $this->importBrands(),
            'groups'     => fn () => $this->importGroups(),
            'categories' => fn () => $this->importCategories(),
            'attributes' => fn () => $this->importAttributes(),
            'products'   => fn () => $this->importProducts(),
            'posts'      => fn () => $this->importPosts(),
            'pages'      => fn () => $this->importPages(),
            'menus'      => fn () => $this->importMenus(),
            'redirects'  => fn () => $this->importRedirects(),
        ];

        foreach ($steps as $name => $step) {
            if ($only && $only !== $name) {
                continue;
            }
            $this->components->task("import {$name}", $step);
        }

        $this->newLine();
        $this->summary();

        return self::SUCCESS;
    }

    private function fresh(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ([
            'attribute_value_product', 'category_product', 'post_category_post',
            'product_variants', 'products', 'categories', 'product_groups', 'brands',
            'attribute_values', 'attributes', 'posts', 'post_categories', 'pages',
            'menu_items', 'redirects',
        ] as $t) {
            DB::table($t)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->warn('Imported tables truncated.');
    }

    // ------------------------------------------------------------------ steps

    private function importBrands(): void
    {
        $rows = $this->wp->query(
            "SELECT term_id, name, slug, description, count
             FROM wp_terms WHERE taxonomy = 'product_brand' ORDER BY count DESC"
        )->fetchAll();

        foreach ($rows as $i => $r) {
            Brand::updateOrCreate(
                ['wp_term_id' => $r['term_id']],
                [
                    'slug'        => $r['slug'],
                    'name'        => Str::title($r['name']),
                    'description' => $r['description'] ?: null,
                    'sort'        => $i,
                ]
            );
        }
    }

    private function importGroups(): void
    {
        foreach (array_values(self::GROUPS) as $i => [$name, $slugs]) {
            $slug = array_keys(self::GROUPS)[$i];
            ProductGroup::updateOrCreate(['slug' => $slug], ['name' => $name, 'sort' => $i]);
        }
    }

    private function importCategories(): void
    {
        $groupOf = [];
        foreach (self::GROUPS as $gslug => [$name, $slugs]) {
            foreach ($slugs as $s) {
                $groupOf[$s] = $gslug;
            }
        }
        $groups = ProductGroup::pluck('id', 'slug');

        $rows = $this->wp->query(
            "SELECT term_id, name, slug, description, count, term_order
             FROM wp_terms WHERE taxonomy = 'product_cat' ORDER BY name"
        )->fetchAll();

        foreach ($rows as $r) {
            if (array_key_exists($r['slug'], self::DROP_CATEGORIES)) {
                continue;
            }

            Category::updateOrCreate(
                ['wp_term_id' => $r['term_id']],
                [
                    'slug'             => $this->normaliseSlug($r['slug']),
                    'name'             => $r['name'],
                    'description'      => $r['description'] ?: null,
                    'product_group_id' => $groups[$groupOf[$r['slug']] ?? ''] ?? null,
                    'sort'             => (int) $r['term_order'],
                    'is_visible'       => (int) $r['count'] > 0,
                ]
            );
        }
    }

    private function importAttributes(): void
    {
        foreach (self::ATTRIBUTE_LABELS as $tax => $label) {
            $attr = Attribute::updateOrCreate(['slug' => Str::after($tax, 'pa_')], ['name' => $label]);

            $stmt = $this->wp->prepare(
                'SELECT term_id, name, slug, term_order FROM wp_terms WHERE taxonomy = ? ORDER BY name'
            );
            $stmt->execute([$tax]);

            foreach ($stmt->fetchAll() as $r) {
                AttributeValue::updateOrCreate(
                    ['attribute_id' => $attr->id, 'slug' => $r['slug']],
                    ['value' => $r['name'], 'sort' => (int) $r['term_order'], 'wp_term_id' => $r['term_id']]
                );
            }
        }
    }

    private function importProducts(): void
    {
        $rows = $this->wp->query(
            "SELECT id, post_title, post_name, post_content, post_excerpt, menu_order,
                    post_date, post_modified
             FROM wp_posts WHERE post_type = 'product' AND post_status = 'publish'
             ORDER BY menu_order, post_title"
        )->fetchAll();

        $brands     = Brand::pluck('id', 'wp_term_id');
        $categories = Category::pluck('id', 'wp_term_id');
        $attrValues = AttributeValue::pluck('id', 'wp_term_id');
        $catRemap   = Category::pluck('id', 'slug');

        // Group legacy SKUs by their parsed base title so pack sizes become variants.
        $grouped = [];
        foreach ($rows as $r) {
            $parsed = PackSizeParser::parse($r['post_title']);
            $grouped[$parsed['base']][] = $r + ['_parsed' => $parsed];
        }

        $bar = $this->output->createProgressBar(count($grouped));

        foreach ($grouped as $base => $skus) {
            $first = $skus[0];
            $meta  = $this->meta($first['id']);

            $product = Product::updateOrCreate(
                ['wp_post_id' => $first['id']],
                [
                    'slug'               => $this->uniqueSlug(Product::class, $first['post_name'], $first['id']),
                    'title'              => count($skus) > 1 ? $base : html_entity_decode($first['post_title']),
                    'base_title'         => $base,
                    'short_description'  => $this->clean($first['post_excerpt']),
                    'description'        => $this->clean($first['post_content']),
                    'brand_id'           => $brands[$this->termOf($first['id'], 'product_brand')] ?? null,
                    'primary_image_path' => $this->imagePath($meta['_thumbnail_id'] ?? null),
                    'gallery'            => $this->galleryPaths($meta['_product_image_gallery'] ?? null),
                    'custom_attributes'  => $this->customAttributes($meta['_product_attributes'] ?? null),
                    'technical_specs'    => $this->blankToNull($meta['_technical_specs'] ?? null),
                    'sort'               => (int) $first['menu_order'],
                    'published_at'       => $first['post_date'],
                ]
            );

            // ---- variants (one per legacy SKU, even when there is only one) ----
            $keepVariantIds = [];
            foreach ($skus as $i => $sku) {
                $m = $this->meta($sku['id']);
                $p = $sku['_parsed'];

                $variant = ProductVariant::updateOrCreate(
                    ['wp_post_id' => $sku['id']],
                    [
                        'product_id'    => $product->id,
                        'sku'           => $m['_sku'] ?? null,
                        'pack_size'     => $p['pack_size'],
                        'pack_format'   => $p['format'],
                        'pack_quantity' => $p['quantity'],
                        'pack_weight_g' => $p['weight_g'],
                        'image_path'      => $this->imagePath($m['_thumbnail_id'] ?? null),
                        'technical_specs' => $this->blankToNull($m['_technical_specs'] ?? null),
                        'sort'            => $i,
                        'stock_status'  => $m['_stock_status'] ?? 'instock',
                        // NOTE: the WP export contains no pricing whatsoever.
                        // price_cents stays NULL until a price list is imported.
                        'is_purchasable' => false,
                    ]
                );
                $keepVariantIds[] = $variant->id;
            }
            $product->variants()->whereNotIn('id', $keepVariantIds)->delete();

            // ---- taxonomy relations, merged across every SKU in the group ----
            $catIds = $attrIds = [];
            foreach ($skus as $sku) {
                foreach ($this->terms($sku['id']) as $t) {
                    if ($t['taxonomy'] === 'product_cat') {
                        $slug = $this->wpSlug($t['term_id']);
                        if (array_key_exists($slug, self::DROP_CATEGORIES)) {
                            $target = self::DROP_CATEGORIES[$slug];
                            if ($target && isset($catRemap[$target])) {
                                $catIds[] = $catRemap[$target];
                            }

                            continue;
                        }
                        if (isset($categories[$t['term_id']])) {
                            $catIds[] = $categories[$t['term_id']];
                        }
                    } elseif (isset($attrValues[$t['term_id']])) {
                        $attrIds[] = $attrValues[$t['term_id']];
                    }
                }
            }
            $product->categories()->sync(array_unique($catIds));
            $product->attributeValues()->sync(array_unique($attrIds));

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function importPosts(): void
    {
        foreach ($this->wp->query(
            "SELECT term_id, name, slug, description FROM wp_terms
             WHERE taxonomy = 'category' AND slug != 'uncategorized'"
        )->fetchAll() as $r) {
            PostCategory::updateOrCreate(
                ['wp_term_id' => $r['term_id']],
                ['slug' => $r['slug'], 'name' => $r['name'], 'description' => $r['description'] ?: null]
            );
        }

        $cats = PostCategory::pluck('id', 'wp_term_id');

        foreach ($this->wp->query(
            "SELECT id, post_title, post_name, post_content, post_excerpt, post_date
             FROM wp_posts WHERE post_type = 'post' AND post_status = 'publish'
             ORDER BY post_date DESC"
        )->fetchAll() as $r) {
            $meta = $this->meta($r['id']);

            $post = Post::updateOrCreate(
                ['wp_post_id' => $r['id']],
                [
                    'slug'                => $this->uniqueSlug(Post::class, $r['post_name'], $r['id']),
                    'title'               => html_entity_decode($r['post_title']),
                    'excerpt'             => $this->clean($r['post_excerpt']),
                    'body'                => $this->clean($r['post_content']),
                    'featured_image_path' => $this->imagePath($meta['_thumbnail_id'] ?? null),
                    'published_at'        => $r['post_date'],
                ]
            );

            $ids = [];
            foreach ($this->terms($r['id']) as $t) {
                if ($t['taxonomy'] === 'category' && isset($cats[$t['term_id']])) {
                    $ids[] = $cats[$t['term_id']];
                }
            }
            $post->categories()->sync(array_unique($ids));
        }
    }

    /**
     * Pages are imported as records only. Their layout is rebuilt as Blade —
     * the extracted copy lives in discovery/pages/*.md.
     */
    private function importPages(): void
    {
        $keep = [
            'home-6'           => ['home', 'Home'],
            'about'            => ['about', 'About'],
            'ceylon-tea'       => ['ceylon-tea', 'Ceylon Tea'],
            'health-benefits'  => ['health-benefits', 'Health Benefits'],
            'faqs'             => ['faqs', 'FAQs'],
            'team'             => ['team', 'Team'],
            'private-labels'   => ['private-label', 'Private Label'],
            'contact'          => ['contact', 'Contact'],
        ];

        foreach ($this->wp->query(
            "SELECT id, post_title, post_name, post_content, post_excerpt, post_date
             FROM wp_posts WHERE post_type = 'page' AND post_status = 'publish'"
        )->fetchAll() as $r) {
            if (! isset($keep[$r['post_name']])) {
                continue;
            }
            [$slug, $title] = $keep[$r['post_name']];

            Page::updateOrCreate(
                ['slug' => $slug],
                [
                    'title'        => $title,
                    'template'     => $slug,
                    'excerpt'      => $this->clean($r['post_excerpt']),
                    'body'         => $this->clean($r['post_content']),
                    'published_at' => $r['post_date'],
                    'wp_post_id'   => $r['id'],
                ]
            );
        }
    }

    private function importMenus(): void
    {
        $map = [
            'http://regencyteas.com/about/'                  => '/about',
            'http://regencyteas.com/product-brand/hyleys/'    => '/brands/hyleys',
            'http://regencyteas.com/our-capabilities/'        => '/about#capabilities',
            'http://regencyteas.com/ceylon-tea/'              => '/ceylon-tea',
            'http://regencyteas.com/private-labels/'          => '/private-label',
            'http://regencyteas.com/contact/'                 => '/contact',
            'http://regencyteas.com/media-centre/'            => '/media',
            'http://regencyteas.com/health-benefits/'         => '/health-benefits',
        ];

        foreach ($this->wp->query('SELECT * FROM wp_menu_items ORDER BY sort')->fetchAll() as $r) {
            $url = $r['type'] === 'post_type' ? '/' : ($map[$r['url']] ?? $r['url']);

            MenuItem::updateOrCreate(
                ['menu' => $r['menu_slug'] === 'main-menu' ? 'main' : 'footer', 'label' => $r['label']],
                ['url' => $url, 'sort' => (int) $r['sort']]
            );
        }
    }

    /** Seeds the redirect table from discovery/url-map.csv. */
    private function importRedirects(): void
    {
        $csv = base_path('../discovery/url-map.csv');
        if (! is_file($csv)) {
            $this->warn("  url-map.csv not found at {$csv} — skipping redirects");

            return;
        }

        $fh   = fopen($csv, 'r');
        $head = fgetcsv($fh);

        while ($row = fgetcsv($fh)) {
            $r    = array_combine($head, $row);
            $from = rtrim($r['url_path'], '/');
            if ($from === '') {
                continue;
            }

            $to = match (true) {
                str_starts_with($from, '/product/')          => '/products/'.basename($from),
                str_starts_with($from, '/product-category/') => '/product-ranges/'.basename($from),
                str_starts_with($from, '/product-brand/')    => '/brands/'.basename($from),
                str_starts_with($from, '/category/')         => '/media/category/'.basename($from),
                str_starts_with($from, '/product-tag/')      => '/products?tag='.basename($from),
                (bool) preg_match('#^/\d{4}/\d{2}/\d{2}/(.+)$#', $from, $m) => '/media/'.$m[1],
                default                                      => null,
            };

            $to ??= match ($from) {
                '/home-6'                   => '/',
                '/shop'                     => '/products',
                '/media-centre'             => '/media',
                '/private-labels'           => '/private-label',
                // Routes renamed during the rebuild — without these the old
                // URLs 404, because the new slug no longer matches.
                '/our-capabilities'         => '/capabilities',
                '/teas-product-catalogue'   => '/catalogue',
                '/product-catalogue-hyleys' => '/catalogue',
                default                     => null,
            };

            // Junk pages: gone, not moved.
            $gone = in_array($from, ['/test', '/shop-2', '/our-capabilities-2', '/teas-product-catalogue-2'], true);

            if (! $to && ! $gone) {
                continue;
            }

            Redirect::updateOrCreate(
                ['from_path' => $from],
                [
                    'to_path'     => $gone ? '/' : $to,
                    'status_code' => $gone ? 410 : 301,
                    'note'        => $r['post_type'],
                ]
            );
        }
        fclose($fh);
    }

    // ------------------------------------------------------------------ helpers

    private array $metaCache = [];

    private function meta(int|string $postId): array
    {
        if (! isset($this->metaCache[$postId])) {
            $stmt = $this->wp->prepare('SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id = ?');
            $stmt->execute([$postId]);
            $this->metaCache[$postId] = array_column($stmt->fetchAll(), 'meta_value', 'meta_key');
        }

        return $this->metaCache[$postId];
    }

    private function terms(int|string $postId): array
    {
        $stmt = $this->wp->prepare('SELECT term_id, taxonomy FROM wp_term_relationships WHERE object_id = ?');
        $stmt->execute([$postId]);

        return $stmt->fetchAll();
    }

    private function termOf(int|string $postId, string $taxonomy): ?int
    {
        foreach ($this->terms($postId) as $t) {
            if ($t['taxonomy'] === $taxonomy) {
                return (int) $t['term_id'];
            }
        }

        return null;
    }

    private function wpSlug(int|string $termId): string
    {
        $stmt = $this->wp->prepare('SELECT slug FROM wp_terms WHERE term_id = ?');
        $stmt->execute([$termId]);

        return (string) $stmt->fetchColumn();
    }

    private function imagePath(?string $attachmentId): ?string
    {
        if (! $attachmentId) {
            return null;
        }
        $stmt = $this->wp->prepare('SELECT file FROM wp_attachments WHERE id = ?');
        $stmt->execute([$attachmentId]);

        return $stmt->fetchColumn() ?: null;
    }

    private function galleryPaths(?string $csv): ?array
    {
        if (! $csv) {
            return null;
        }
        $paths = array_filter(array_map(fn ($id) => $this->imagePath(trim($id)), explode(',', $csv)));

        return $paths ? array_values($paths) : null;
    }

    private function blankToNull(?string $v): ?string
    {
        $v = trim((string) $v);

        return $v === '' ? null : $v;
    }

    /**
     * Unserialises WooCommerce's `_product_attributes` blob and keeps only
     * custom (non-taxonomy) attributes — taxonomy ones are already modelled
     * properly in attribute_values.
     */
    private function customAttributes(?string $serialised): ?array
    {
        if (! $serialised) {
            return null;
        }

        $data = @unserialize($serialised);
        if (! is_array($data)) {
            return null;
        }

        $out = [];
        foreach ($data as $key => $attr) {
            if (! is_array($attr) || ! empty($attr['is_taxonomy'])) {
                continue;
            }
            $name  = $attr['name'] ?? $key;
            $value = trim((string) ($attr['value'] ?? ''));
            if ($value !== '') {
                $out[$name] = array_values(array_filter(array_map('trim', explode('|', $value))));
            }
        }

        return $out ?: null;
    }

    /** Strip WordPress shortcodes and Elementor CSS artefacts. */
    private function clean(?string $html): ?string
    {
        if (! $html) {
            return null;
        }
        $html = preg_replace('/\[\/?[a-z0-9_\-]+[^\]]*\]/i', ' ', $html);
        $html = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', '', $html);
        $html = preg_replace('#/\*.*?\*/#s', '', $html);
        $html = preg_replace('/\s{2,}/', ' ', $html);

        return trim($html) ?: null;
    }

    private function normaliseSlug(string $slug): string
    {
        return Str::slug(str_replace('_', '-', $slug));
    }

    private function uniqueSlug(string $model, ?string $slug, int|string $wpId): string
    {
        $slug = $this->normaliseSlug($slug ?: 'item-'.$wpId);
        $base = $slug;
        $i    = 2;

        while ($model::where('slug', $slug)->where('wp_post_id', '!=', $wpId)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    private function summary(): void
    {
        $this->table(['table', 'rows'], [
            ['brands', Brand::count()],
            ['product_groups', ProductGroup::count()],
            ['categories', Category::count()],
            ['products', Product::count()],
            ['product_variants', ProductVariant::count()],
            ['attributes', Attribute::count()],
            ['attribute_values', AttributeValue::count()],
            ['posts', Post::count()],
            ['post_categories', PostCategory::count()],
            ['pages', Page::count()],
            ['menu_items', MenuItem::count()],
            ['redirects', Redirect::count()],
        ]);

        $priced = ProductVariant::whereNotNull('price_cents')->count();
        $this->warn("Variants with a price: {$priced} / ".ProductVariant::count()
            .'  — the WordPress export contains no pricing data (see PHASE2-MIGRATION-PLAN.md §0).');
    }
}
