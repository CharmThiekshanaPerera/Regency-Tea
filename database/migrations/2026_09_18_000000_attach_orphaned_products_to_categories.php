<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Three products were tagged only "New Arrivals" in WooCommerce — a
 * promotional term that ImportWordPress::DROP_CATEGORIES maps to null, so
 * they came out of the import with zero rows in category_product and are
 * invisible on every per-category range page (they still show under "All
 * Products" and search, since those don't filter by category).
 *
 * Resolved by name rather than id so this survives a rebuild from the
 * importer, which reassigns primary keys.
 */
return new class extends Migration
{
    private const MAP = [
        'COLON CLEANSE 2 FLAVOR ASSORTMENT' => 'Colon Clense',
        'SLEEP RITUALS HERBAL TEA' => 'Sleep Tea',
        'SLIM TEA 2 FLAVOR ASSORTMENT' => 'Slim Tea',
    ];

    public function up(): void
    {
        foreach (self::MAP as $productName => $categoryName) {
            $product = $this->findProduct($productName);
            $category = $this->findCategory($categoryName);

            if (! $product || ! $category) {
                Log::warning('attach_orphaned_products_to_categories: could not resolve, skipping', [
                    'product' => $productName,
                    'product_found' => (bool) $product,
                    'category' => $categoryName,
                    'category_found' => (bool) $category,
                ]);

                continue;
            }

            DB::table('category_product')->insertOrIgnore([
                'product_id' => $product->id,
                'category_id' => $category->id,
            ]);
        }
    }

    public function down(): void
    {
        foreach (self::MAP as $productName => $categoryName) {
            $product = $this->findProduct($productName);
            $category = $this->findCategory($categoryName);

            if (! $product || ! $category) {
                continue;
            }

            DB::table('category_product')
                ->where('product_id', $product->id)
                ->where('category_id', $category->id)
                ->delete();
        }
    }

    private function findProduct(string $baseTitle): ?Product
    {
        return Product::whereRaw('LOWER(base_title) = ?', [Str::lower($baseTitle)])->first();
    }

    private function findCategory(string $name): ?Category
    {
        return Category::all()
            ->first(fn (Category $c) => Str::lower($c->getTranslation('name', 'en')) === Str::lower($name))
            ?? Category::where('slug', Str::slug($name))->first();
    }
};
