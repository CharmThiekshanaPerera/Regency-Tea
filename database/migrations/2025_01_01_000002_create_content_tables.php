<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Editorial content: pages, blog posts (Media Centre) and their categories.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('template')->default('default')
                  ->comment('Blade view under resources/views/pages');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable()
                  ->comment('markdown extracted from the Elementor layout');
            $table->json('blocks')->nullable()
                  ->comment('structured content blocks once rebuilt in Blade');
            $table->string('hero_image_path')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image_path')->nullable();
            $table->boolean('is_indexable')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedBigInteger('wp_post_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('post_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->unsignedBigInteger('wp_term_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('featured_image_path')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedBigInteger('wp_post_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('post_category_post', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_category_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'post_category_id'], 'post_cat_pk');
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('menu')->index()->comment('main | footer');
            $table->string('label');
            $table->string('url');
            $table->foreignId('parent_id')->nullable()
                  ->constrained('menu_items')->cascadeOnDelete();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('post_category_post');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_categories');
        Schema::dropIfExists('pages');
    }
};
