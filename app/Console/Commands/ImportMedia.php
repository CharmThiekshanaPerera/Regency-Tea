<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Copies the referenced WordPress uploads into public/media and links them to
 * products, variants and posts.
 *
 * Images are served straight from public/ rather than the `public` storage disk
 * so the site never depends on `php artisan storage:link` — that symlink is a
 * common silent failure on shared/cPanel hosting. See App\Support\Media.
 *
 * Reads discovery/media-files.csv (produced in Phase 1), which classifies all
 * 21,394 upload files as referenced / thumbnail-of-referenced / orphaned.
 * Only originals are copied — WordPress generated 11,924 -WxH derivatives that
 * Laravel regenerates on demand.
 *
 * _backup/ is READ-ONLY. This command only ever reads from it.
 *
 *   php artisan wp:media
 *   php artisan wp:media --dry-run
 *   php artisan wp:media --all       # include orphans too
 */
class ImportMedia extends Command
{
    protected $signature = 'wp:media
                            {--dry-run : report what would be copied, copy nothing}
                            {--all : also copy files classified as orphaned}
                            {--force : overwrite files already present}';

    protected $description = 'Copy referenced WordPress media into public/media and attach it to content';

    /** WordPress thumbnail derivative, e.g. product-600x600.jpg */
    private const DERIVATIVE = '/-\d{2,4}x\d{2,4}\.(jpe?g|png|gif|webp)$/i';

    private int $copied = 0;
    private int $skipped = 0;
    private int $missing = 0;
    private int $bytes = 0;

    public function handle(): int
    {
        $uploads = rtrim(env('WP_UPLOADS_PATH', base_path('../_backup/wp-content/uploads')), '/\\');
        $csv     = base_path('../discovery/media-files.csv');

        if (! is_dir($uploads)) {
            $this->error("Uploads folder not found: {$uploads}");
            $this->line('Set WP_UPLOADS_PATH in .env to the absolute path of _backup/wp-content/uploads');

            return self::FAILURE;
        }

        if (! is_file($csv)) {
            $this->error("Missing {$csv} — run the Phase 1 media inventory first.");

            return self::FAILURE;
        }

        $this->components->info('Reading media inventory…');
        $wanted = $this->wantedFiles($csv);
        $this->line(sprintf('  %s files to migrate (of 21,394 total)', number_format(count($wanted))));

        if ($this->option('dry-run')) {
            $this->warn('  DRY RUN — nothing will be written');
        }

        $this->copyFiles($uploads, $wanted);
        $this->newLine();

        if (! $this->option('dry-run')) {
            $this->components->task('link product images', fn () => $this->linkProducts());
            $this->components->task('link post images', fn () => $this->linkPosts());
            $this->components->task('verify every product has an image', fn () => $this->verify());
        }

        $this->newLine();
        $this->table(['metric', 'value'], [
            ['files copied', number_format($this->copied)],
            ['already present (skipped)', number_format($this->skipped)],
            ['referenced but MISSING from backup', number_format($this->missing)],
            ['total size', $this->human($this->bytes)],
            ['destination', 'public/'.Media::DIR],
        ]);

        if ($this->missing > 0) {
            $this->warn("{$this->missing} referenced files were not found in the backup — see storage/logs for the list.");
        }

        return self::SUCCESS;
    }

    /** @return array<string,true> relative upload paths worth copying */
    private function wantedFiles(string $csv): array
    {
        $fh   = fopen($csv, 'r');
        $head = fgetcsv($fh);
        $want = [];

        while ($row = fgetcsv($fh)) {
            $r      = array_combine($head, $row);
            $path   = $r['path'];
            $status = $r['status'];

            // Never copy WordPress's generated size variants.
            if (preg_match(self::DERIVATIVE, $path)) {
                continue;
            }

            // Plugin cache and log directories are not content.
            if (preg_match('#^(wc-logs|wpc-ajax-search|elementor|litespeed|pum|sass|merlin-wp|wpvivid)/#i', $path)) {
                continue;
            }

            if ($status === 'referenced' || $this->option('all')) {
                $want[$path] = true;
            }
        }
        fclose($fh);

        return $want;
    }

    private function copyFiles(string $uploads, array $wanted): void
    {
        $missing = [];
        $bar     = $this->output->createProgressBar(count($wanted));
        $bar->start();

        foreach (array_keys($wanted) as $rel) {
            $src  = $uploads.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $rel);
            $dest = public_path(Media::DIR.'/'.$rel);

            if (! is_file($src)) {
                $this->missing++;
                $missing[] = $rel;
                $bar->advance();

                continue;
            }

            if (is_file($dest) && ! $this->option('force')) {
                $this->skipped++;
                $bar->advance();

                continue;
            }

            if (! $this->option('dry-run')) {
                File::ensureDirectoryExists(dirname($dest));
                // copy() streams internally, so multi-MB PDFs never hit memory.
                copy($src, $dest);
            }

            $this->copied++;
            $this->bytes += filesize($src);
            $bar->advance();
        }

        $bar->finish();

        if ($missing) {
            File::put(storage_path('logs/media-missing.txt'), implode(PHP_EOL, $missing));
        }
    }

    /**
     * Normalises stored WordPress paths and drops any reference whose file did
     * not make it into public/media.
     */
    private function linkProducts(): void
    {
        Product::query()->chunkById(200, function ($products) {
            foreach ($products as $product) {
                $primary = $this->resolve($product->primary_image_path);

                $gallery = collect($product->gallery ?? [])
                    ->map(fn ($p) => $this->resolve($p))
                    ->filter()
                    ->values()
                    ->all();

                // Fall back to the first variant image if the product has none.
                if (! $primary) {
                    $primary = $product->variants
                        ->map(fn ($v) => $this->resolve($v->image_path))
                        ->filter()
                        ->first();
                }

                $product->forceFill([
                    'primary_image_path' => $primary,
                    'gallery'            => $gallery ?: null,
                ])->saveQuietly();
            }
        });

        ProductVariant::query()->chunkById(500, function ($variants) {
            foreach ($variants as $variant) {
                $variant->forceFill([
                    'image_path' => $this->resolve($variant->image_path),
                ])->saveQuietly();
            }
        });
    }

    private function linkPosts(): void
    {
        Post::query()->chunkById(200, function ($posts) {
            foreach ($posts as $post) {
                $post->forceFill([
                    'featured_image_path' => $this->resolve($post->featured_image_path),
                ])->saveQuietly();
            }
        });
    }

    /**
     * Normalises a stored WordPress path and keeps it only if the file actually
     * landed in public/media. Paths are stored WITHOUT the "media/" prefix so
     * that App\Support\Media owns URL construction in one place.
     */
    private function resolve(?string $wpPath): ?string
    {
        $clean = Media::normalise($wpPath);

        if ($clean === null) {
            return null;
        }

        return Media::exists($clean) ? $clean : null;
    }

    private function verify(): void
    {
        $without = Product::whereNull('primary_image_path')->count();
        $total   = Product::count();

        if ($without > 0) {
            $this->newLine();
            $this->warn("  {$without} of {$total} products have no image after linking.");
            Product::whereNull('primary_image_path')
                ->take(10)->pluck('slug')
                ->each(fn ($s) => $this->line("    - {$s}"));
        }
    }

    private function human(int $bytes): string
    {
        foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
            if ($bytes < 1024) {
                return round($bytes, 1).' '.$unit;
            }
            $bytes /= 1024;
        }

        return round($bytes, 1).' TB';
    }
}
