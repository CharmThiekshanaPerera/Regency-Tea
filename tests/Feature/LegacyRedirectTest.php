<?php

namespace Tests\Feature;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegacyRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_legacy_url_redirects(): void
    {
        Redirect::create([
            'from_path' => '/2023/10/08/prodexpo-2024',
            'to_path'   => '/media/prodexpo-2024',
        ]);

        $this->get('/2023/10/08/prodexpo-2024')
             ->assertRedirect('/media/prodexpo-2024')
             ->assertStatus(301);
    }

    public function test_junk_pages_return_gone(): void
    {
        Redirect::create([
            'from_path' => '/test', 'to_path' => '/', 'status_code' => 410,
        ]);

        $this->get('/test')->assertStatus(410);
    }

    public function test_hits_are_counted(): void
    {
        $redirect = Redirect::create(['from_path' => '/shop', 'to_path' => '/products']);

        $this->get('/shop');

        $this->assertSame(1, $redirect->fresh()->hits);
    }

    public function test_unknown_urls_still_404(): void
    {
        $this->get('/nothing-here')->assertNotFound();
    }
}
