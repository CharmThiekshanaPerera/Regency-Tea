<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Build public/sitemap.xml from published content';

    public function handle(): int
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('home'))->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        foreach (['about', 'ceylon-tea', 'health-benefits', 'private-label',
                  'faqs', 'team', 'capabilities', 'contact', 'catalogue'] as $name) {
            if (Page::published()->where('slug', $name === 'home' ? 'home' : $name)->exists()
                || in_array($name, ['contact', 'catalogue'], true)) {
                $sitemap->add(Url::create(url("/{$name}"))->setPriority(0.8));
            }
        }

        $sitemap->add(Url::create(route('ranges'))->setPriority(0.9));
        $sitemap->add(Url::create(route('products'))->setPriority(0.9));
        $sitemap->add(Url::create(route('media'))->setPriority(0.7));

        Category::visible()->each(fn ($c) => $sitemap->add(
            Url::create(route('range.show', $c))->setPriority(0.7)));

        Brand::each(fn ($b) => $sitemap->add(
            Url::create(route('brand.show', $b))->setPriority(0.8)));

        Product::published()->chunk(200, fn ($chunk) => $chunk->each(fn ($p) => $sitemap->add(
            Url::create(route('product.show', $p))
                ->setLastModificationDate($p->updated_at)
                ->setPriority(0.6))));

        Post::published()->each(fn ($p) => $sitemap->add(
            Url::create(route('media.show', $p))
                ->setLastModificationDate($p->updated_at)
                ->setPriority(0.5)));

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('sitemap.xml written ('.count($sitemap->getTags()).' URLs)');

        return self::SUCCESS;
    }
}
