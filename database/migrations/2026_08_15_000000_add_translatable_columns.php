<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Converts the text columns that carry user-facing copy into JSON columns
 * holding one value per locale (e.g. {"en": "...", "fr": "..."}), consumed by
 * spatie/laravel-translatable's HasTranslations trait on each model.
 *
 * Column type changes go through an intermediate TEXT widening step before
 * the JSON conversion — the DB would otherwise validate existing plain-text
 * data against the JSON type on a direct ALTER, and a value re-wrapped as
 * {"en":"..."} is a few bytes longer than the original, which could overflow
 * a tight VARCHAR(255) limit anyway. Uses the Schema Builder (needs
 * doctrine/dbal for ->change()) rather than raw ALTER TABLE SQL so this runs
 * the same way against both MySQL (prod/local) and SQLite (the test suite).
 */
return new class extends Migration
{
    /** @var array<string, list<string>> */
    private array $columns = [
        'brands'           => ['description', 'meta_title', 'meta_description'],
        'product_groups'   => ['name'],
        'categories'       => ['name', 'description', 'meta_title', 'meta_description'],
        'products'         => ['title', 'short_description', 'description', 'meta_title', 'meta_description'],
        'pages'            => ['title', 'excerpt', 'body', 'meta_title', 'meta_description'],
        'post_categories'  => ['name', 'description'],
        'posts'            => ['title', 'excerpt', 'body', 'meta_title', 'meta_description'],
        'menu_items'       => ['label'],
        'attributes'       => ['name'],
        'attribute_values' => ['value'],
    ];

    public function up(): void
    {
        foreach ($this->columns as $table => $cols) {
            Schema::table($table, function (Blueprint $t) use ($cols) {
                foreach ($cols as $col) {
                    $t->text($col)->nullable()->change();
                }
            });
        }

        foreach ($this->columns as $table => $cols) {
            foreach ($cols as $col) {
                DB::table($table)->whereNotNull($col)->where($col, '!=', '')
                    ->orderBy('id')->chunkById(200, function ($rows) use ($table, $col) {
                        foreach ($rows as $row) {
                            DB::table($table)->where('id', $row->id)->update([
                                $col => json_encode(['en' => $row->$col], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                            ]);
                        }
                    });
            }
        }

        foreach ($this->columns as $table => $cols) {
            Schema::table($table, function (Blueprint $t) use ($cols) {
                foreach ($cols as $col) {
                    $t->json($col)->nullable()->change();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $table => $cols) {
            Schema::table($table, function (Blueprint $t) use ($cols) {
                foreach ($cols as $col) {
                    $t->text($col)->nullable()->change();
                }
            });

            foreach ($cols as $col) {
                DB::table($table)->whereNotNull($col)->where($col, '!=', '')
                    ->orderBy('id')->chunkById(200, function ($rows) use ($table, $col) {
                        foreach ($rows as $row) {
                            $decoded = json_decode((string) $row->$col, true);
                            DB::table($table)->where('id', $row->id)->update([
                                $col => is_array($decoded) ? ($decoded['en'] ?? array_values($decoded)[0] ?? null) : $row->$col,
                            ]);
                        }
                    });
            }
        }
    }
};
