<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Slider;
use App\Support\Media;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $groups = ProductGroup::with('categories')->orderBy('sort')->get();

        $groups->each(function (ProductGroup $group) {
            $categoryIds = $group->categories->pluck('id');

            $group->cover_image = Product::published()
                ->whereNotNull('primary_image_path')
                ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds))
                ->orderByDesc('published_at')
                ->value('primary_image_path');
        });

        return view('pages.home', [
            'page'        => Page::published()->where('slug', 'home')->first(),
            'slides'      => Slider::where('slug', 'home-hero')->first()?->slides ?? collect(),
            'brands'      => Brand::withCount('products')->orderBy('sort')->get(),
            'newArrivals' => Product::newArrivals()->with(['brand', 'variants'])
                                    ->latest('published_at')->take(8)->get(),
            'groups'      => $groups,
            'latestPosts' => Post::published()->latest('published_at')->take(3)->get(),
            'stats'       => [
                'years'     => now()->year - 1997,
                'countries' => 35,
                'brands'    => Brand::count(),
                'products'  => Product::published()->count(),
            ],
        ]);
    }

    public function show(string $slug): View
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        $view = view()->exists("pages.{$page->template}")
            ? "pages.{$page->template}"
            : 'pages.default';

        return view($view, compact('page'));
    }

    /**
     * The legacy site used a 3D flipbook plugin for two catalogue PDFs.
     * Replaced with plain downloads — see PRODUCTION-BUILD-PLAN.md §2.5.
     */
    public function catalogue(): View
    {
        $files = [
            ['title' => 'Regency Teas — full catalogue', 'path' => '2026/01/RTC2026.pdf'],
            ['title' => 'Hyleys catalogue',              'path' => '2024/09/RT-CAT-HY.pdf'],
        ];

        $catalogues = collect($files)
            ->filter(fn ($f) => Media::exists($f['path']))
            ->map(fn ($f) => [
                'title' => $f['title'],
                'url'   => Media::url($f['path']),
                'size'  => $this->human((int) @filesize(Media::path($f['path']))),
            ])
            ->values()
            ->all();

        return view('pages.catalogue', [
            'page'       => Page::published()->where('slug', 'catalogue')->first(),
            'catalogues' => $catalogues,
        ]);
    }

    private function human(int $bytes): string
    {
        foreach (['B', 'KB', 'MB'] as $unit) {
            if ($bytes < 1024) {
                return round($bytes, 1).' '.$unit;
            }
            $bytes /= 1024;
        }

        return round($bytes, 1).' GB';
    }
}
