<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@regencyteas.com'],
            ['name' => 'Administrator', 'password' => Hash::make(env('ADMIN_PASSWORD', 'change-me-now'))]
        );

        Slider::firstOrCreate(['slug' => 'home-hero'], ['name' => 'Homepage hero']);

        // Pages the legacy site never had, so the routes resolve from day one.
        foreach ([
            'capabilities' => 'Our Capabilities',
            'catalogue'    => 'Product Catalogue',
        ] as $slug => $title) {
            Page::firstOrCreate(['slug' => $slug], [
                'title'        => $title,
                'template'     => $slug,
                'published_at' => now(),
            ]);
        }
    }
}
