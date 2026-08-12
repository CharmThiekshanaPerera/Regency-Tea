<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Product;
use App\Support\Media;
use Illuminate\Console\Command;

/**
 * Confirms that every image path in the database resolves to a file that is
 * actually present in public/media. Run this on the server after uploading the
 * media bundle — it is the fastest way to prove images will render.
 *
 *   php artisan media:verify
 *   php artisan media:verify --list   # print every broken path
 */
class VerifyMedia extends Command
{
    protected $signature = 'media:verify {--list : print every missing file}';

    protected $description = 'Check that all database image references exist in public/media';

    public function handle(): int
    {
        $dir = public_path(Media::DIR);

        if (! is_dir($dir)) {
            $this->error("public/".Media::DIR." does not exist.");
            $this->line('  Upload the media bundle, or run `php artisan wp:media` where the backup is available.');

            return self::FAILURE;
        }

        $stats = Media::stats();
        $this->components->info(sprintf(
            'public/%s contains %s files (%s)',
            Media::DIR, number_format($stats['files']), $this->human($stats['bytes'])
        ));

        $missing = [];
        $checked = 0;
        $blank   = ['product' => 0, 'post' => 0];

        Product::query()->chunkById(200, function ($products) use (&$missing, &$checked, &$blank) {
            foreach ($products as $p) {
                if (! $p->primary_image_path) {
                    $blank['product']++;
                } else {
                    $checked++;
                    if (! Media::exists($p->primary_image_path)) {
                        $missing[] = "product #{$p->id} {$p->slug} -> {$p->primary_image_path}";
                    }
                }

                foreach ($p->gallery ?? [] as $g) {
                    $checked++;
                    if (! Media::exists($g)) {
                        $missing[] = "gallery  #{$p->id} {$p->slug} -> {$g}";
                    }
                }
            }
        });

        Post::query()->chunkById(200, function ($posts) use (&$missing, &$checked, &$blank) {
            foreach ($posts as $p) {
                if (! $p->featured_image_path) {
                    $blank['post']++;

                    continue;
                }
                $checked++;
                if (! Media::exists($p->featured_image_path)) {
                    $missing[] = "post    #{$p->id} {$p->slug} -> {$p->featured_image_path}";
                }
            }
        });

        $this->newLine();
        $this->table(['check', 'result'], [
            ['image references checked', number_format($checked)],
            ['resolved to a real file', number_format($checked - count($missing))],
            ['MISSING', number_format(count($missing))],
            ['products with no image set', $blank['product']],
            ['posts with no image set', $blank['post']],
        ]);

        if ($missing) {
            $this->error(count($missing).' reference(s) point at files that are not on disk.');

            foreach (array_slice($missing, 0, $this->option('list') ? PHP_INT_MAX : 15) as $m) {
                $this->line('  '.$m);
            }

            if (! $this->option('list') && count($missing) > 15) {
                $this->line('  … re-run with --list for the full list');
            }

            return self::FAILURE;
        }

        $this->components->info('All image references resolve. Images will render.');

        return self::SUCCESS;
    }

    private function human(int $b): string
    {
        foreach (['B', 'KB', 'MB', 'GB'] as $u) {
            if ($b < 1024) {
                return round($b, 1).' '.$u;
            }
            $b /= 1024;
        }

        return round($b, 1).' TB';
    }
}
