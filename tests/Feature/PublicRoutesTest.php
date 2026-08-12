<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_catalogue_routes_load(): void
    {
        $brand    = Brand::create(['slug' => 'hyleys', 'name' => 'Hyleys']);
        $category = Category::create(['slug' => 'earl-grey-tea', 'name' => 'Earl Grey Tea']);

        $product = Product::create([
            'slug' => 'earl-grey-25', 'title' => 'Earl Grey',
            'brand_id' => $brand->id, 'published_at' => now(),
        ]);
        ProductVariant::create([
            'product_id' => $product->id, 'sku' => 'EG-25', 'pack_size' => '25 TEA BAGS',
        ]);
        $product->categories()->attach($category);

        $this->get('/products')->assertOk()->assertSee('Earl Grey');
        $this->get('/products/earl-grey-25')->assertOk()->assertSee('EG-25');
        $this->get('/product-ranges')->assertOk();
        $this->get('/product-ranges/earl-grey-tea')->assertOk();
        $this->get('/brands/hyleys')->assertOk();
    }

    public function test_media_routes_load(): void
    {
        Post::create([
            'slug' => 'a-news-item', 'title' => 'A news item',
            'body' => 'Body copy', 'published_at' => now(),
        ]);

        $this->get('/media')->assertOk()->assertSee('A news item');
        $this->get('/media/a-news-item')->assertOk();
    }

    public function test_content_pages_load(): void
    {
        Page::create([
            'slug' => 'about', 'title' => 'About', 'template' => 'about',
            'body' => '<p>Our story</p>', 'published_at' => now(),
        ]);

        $this->get('/about')->assertOk()->assertSee('Our story', false);
    }

    public function test_unpublished_pages_are_not_reachable(): void
    {
        Page::create(['slug' => 'about', 'title' => 'About', 'published_at' => null]);

        $this->get('/about')->assertNotFound();
    }

    public function test_contact_form_validates_and_stores(): void
    {
        $this->post('/contact', [
            'name' => 'Buyer', 'email' => 'buyer@gmail.com',
            'message' => 'We would like a quotation for bulk Ceylon black tea.',
        ])->assertRedirect();

        $this->assertDatabaseHas('enquiries', ['email' => 'buyer@gmail.com']);
    }

    public function test_contact_form_rejects_a_short_message(): void
    {
        $this->post('/contact', ['name' => 'X', 'email' => 'x@example.com', 'message' => 'hi'])
             ->assertSessionHasErrors('message');
    }

    public function test_honeypot_blocks_bots(): void
    {
        $this->post('/contact', [
            'name' => 'Bot', 'email' => 'bot@example.com',
            'message' => 'Buy cheap things from our website today',
            'website' => 'http://spam.example',
        ])->assertForbidden();
    }

    public function test_commerce_routes_are_disabled_by_default(): void
    {
        $this->assertFalse(config('regency.commerce_enabled'));
        $this->get('/cart')->assertNotFound();
    }
}
