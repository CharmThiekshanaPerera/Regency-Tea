<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fields discovered by reading the legacy teapoz theme rather than the DB alone.
 * See discovery/PRODUCTION-BUILD-PLAN.md §0.3.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // _technical_specs — B2B export case-pack, e.g.
            // "1g x 20 envelope tea bags x 24 inner cartons per MC"
            $table->text('technical_specs')->nullable()->after('pack_weight_g');
        });

        Schema::table('products', function (Blueprint $table) {
            // _product_attributes: custom (non-taxonomy) attributes, name => value
            $table->json('custom_attributes')->nullable()->after('gallery');
            $table->text('technical_specs')->nullable()->after('custom_attributes');
        });

        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slider_id')->constrained()->cascadeOnDelete();
            $table->string('heading')->nullable();
            $table->string('subheading')->nullable();
            $table->text('body')->nullable();
            $table->string('image_path')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->string('group')->default('general')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('slides');
        Schema::dropIfExists('sliders');

        Schema::table('products', fn (Blueprint $t) => $t->dropColumn(['custom_attributes', 'technical_specs']));
        Schema::table('product_variants', fn (Blueprint $t) => $t->dropColumn('technical_specs'));
    }
};
