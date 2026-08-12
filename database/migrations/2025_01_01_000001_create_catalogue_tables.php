<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catalogue core: brands, product groups, categories, products, variants.
 *
 * Departures from the WordPress source (see discovery/PHASE2-MIGRATION-PLAN.md):
 *  - brand is a first-class FK, not a taxonomy (every product has exactly one)
 *  - product_groups is new, to make 72 categories navigable
 *  - pack sizes become product_variants rather than 434 flat "simple" products
 *  - commerce columns are nullable: the WP export contains NO pricing data at all
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->unsignedBigInteger('wp_term_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('product_groups', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->foreignId('product_group_id')->nullable()
                  ->constrained('product_groups')->nullOnDelete();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->unsignedBigInteger('wp_term_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('base_title')->nullable()
                  ->comment('title with the pack-size suffix stripped');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('primary_image_path')->nullable();
            $table->json('gallery')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedBigInteger('wp_post_id')->nullable()->index();
            $table->timestamps();

            $table->index(['brand_id', 'published_at']);
        });

        // One row per purchasable pack size. Products with a single size still get
        // exactly one variant, so there is only ever one code path.
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->nullable()->index();
            $table->string('pack_size')->nullable()
                  ->comment('e.g. "25 FOIL ENVELOPE TEA BAGS"');
            $table->string('pack_format')->nullable()
                  ->comment('normalised: tea_bags | loose_tea | pyramid | sachets');
            $table->unsignedInteger('pack_quantity')->nullable();
            $table->unsignedInteger('pack_weight_g')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedInteger('sort')->default(0);

            // ---- commerce (Phase C). NULL until a price list exists. ----
            $table->unsignedBigInteger('price_cents')->nullable();
            $table->unsignedBigInteger('sale_price_cents')->nullable();
            $table->char('currency', 3)->nullable();
            $table->integer('stock_qty')->nullable();
            $table->string('stock_status')->default('instock');
            $table->unsignedInteger('weight_g')->nullable();
            $table->string('tax_class')->nullable();
            $table->boolean('is_purchasable')->default(false);

            $table->unsignedBigInteger('wp_post_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('product_groups');
        Schema::dropIfExists('brands');
    }
};
