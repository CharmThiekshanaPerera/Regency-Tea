<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogueController extends Controller
{
    /** Grouped index of every product range. */
    public function ranges(): View
    {
        return view('catalogue.ranges', [
            'groups' => ProductGroup::with([
                'categories' => fn ($q) => $q->visible()->withCount('products'),
            ])->orderBy('sort')->get(),
        ]);
    }

    public function range(Category $category, Request $request): View
    {
        return view('catalogue.range', [
            'category' => $category->load('group'),
            'products' => $this->filtered($category->products(), $request)->paginate(24)->withQueryString(),
            'filters'  => $this->filters(),
        ]);
    }

    public function brand(Brand $brand, Request $request): View
    {
        return view('catalogue.brand', [
            'brand'    => $brand,
            'products' => $this->filtered($brand->products(), $request)->paginate(24)->withQueryString(),
            'filters'  => $this->filters(),
        ]);
    }

    public function index(Request $request): View
    {
        return view('catalogue.index', [
            'products' => $this->filtered(Product::query(), $request)->paginate(24)->withQueryString(),
            'filters'  => $this->filters(),
            'brands'   => Brand::orderBy('sort')->get(),
        ]);
    }

    public function show(Product $product): View
    {
        $product->load(['brand', 'variants', 'categories.group', 'attributeValues.attribute']);

        return view('catalogue.show', [
            'product'  => $product,
            'previous' => $this->adjacent($product, 'previous'),
            'next'     => $this->adjacent($product, 'next'),
            'related'  => Product::published()
                ->where('id', '!=', $product->id)
                ->when($product->brand_id, fn ($q) => $q->where('brand_id', $product->brand_id))
                ->inRandomOrder()->take(4)->get(),
        ]);
    }

    /**
     * Previous / next product, ordered the same way the listing is, and kept
     * within the same brand so the sequence stays meaningful.
     * Replaces the legacy class-woocommerce-adjacent-products.php.
     */
    private function adjacent(Product $product, string $direction): ?Product
    {
        return Product::published()
            ->when($product->brand_id, fn ($q) => $q->where('brand_id', $product->brand_id))
            ->where('id', '!=', $product->id)
            ->when(
                $direction === 'previous',
                fn ($q) => $q->where('title', '<', $product->title)->orderByDesc('title'),
                fn ($q) => $q->where('title', '>', $product->title)->orderBy('title'),
            )
            ->first();
    }

    public function search(Request $request): View
    {
        $term = trim((string) $request->query('q'));

        $products = Product::published()
            ->when($term !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('title', 'like', "%{$term}%")
                ->orWhere('short_description', 'like', "%{$term}%")
                ->orWhereHas('variants', fn ($v) => $v->where('sku', 'like', "%{$term}%"))))
            ->with('brand')
            ->paginate(24)
            ->withQueryString();

        return view('catalogue.search', compact('products', 'term'));
    }

    /** Applies ?tea-type=, ?collection=, ?benefits=, ?packaging=, ?brand=, ?sort= */
    private function filtered($query, Request $request)
    {
        $query->published()->with(['brand', 'variants']);

        foreach (['tea-menu', 'collection', 'benefits', 'packaging-options'] as $slug) {
            if ($value = $request->query($slug)) {
                $query->whereHas('attributeValues', fn ($q) => $q
                    ->where('attribute_values.slug', $value)
                    ->whereHas('attribute', fn ($a) => $a->where('slug', $slug)));
            }
        }

        if ($brand = $request->query('brand')) {
            $query->forBrand($brand);
        }

        return match ($request->query('sort')) {
            'newest' => $query->latest('published_at'),
            'name'   => $query->orderBy('title'),
            default  => $query->orderBy('sort')->orderBy('title'),
        };
    }

    private function filters()
    {
        return Attribute::where('is_filterable', true)
            ->with('values')
            ->orderBy('sort')
            ->get();
    }
}
