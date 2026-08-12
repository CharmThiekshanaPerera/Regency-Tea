<?php

namespace App\Filament\Widgets;

use App\Models\Enquiry;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductVariant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CatalogueOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $missingImages = Product::whereNull('primary_image_path')->count();
        $unhandled     = Enquiry::whereNull('handled_at')->count();

        return [
            Stat::make('Products', Product::count())
                ->description(ProductVariant::count().' pack sizes')
                ->color('primary'),

            Stat::make('Enquiries awaiting reply', $unhandled)
                ->color($unhandled > 0 ? 'warning' : 'success'),

            Stat::make('Products missing an image', $missingImages)
                ->color($missingImages > 0 ? 'danger' : 'success'),

            Stat::make('Published articles', Post::published()->count()),
        ];
    }
}
