<?php

namespace App\Console\Commands;

use App\Support\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Builds a compressed archive of just the media the site actually needs, so it
 * can be shipped to a server over SSH.
 *
 * The full WordPress uploads folder is 21,394 files / 3.85 GB, most of which is
 * generated thumbnails and orphans. The deployable set is ~1,600 originals.
 *
 *   php artisan media:bundle
 *   php artisan media:bundle --skip-pdf     # drops ~80 MB of catalogue PDFs
 *   php artisan media:bundle --out=/tmp/media.tar.gz
 */
class BundleMedia extends Command
{
    protected $signature = 'media:bundle
                            {--out= : output path (default storage/app/regency-media.tar.gz)}
                            {--skip-pdf : exclude PDF catalogues}
                            {--manifest : also write a checksum manifest}';

    protected $description = 'Package public/media into an archive for deployment over SSH';

    public function handle(): int
    {
        $dir = public_path(Media::DIR);

        if (! is_dir($dir)) {
            $this->error("No media directory at {$dir}. Run `php artisan wp:media` first.");

            return self::FAILURE;
        }

        $out = $this->option('out') ?: storage_path('app/regency-media.tar.gz');
        File::ensureDirectoryExists(dirname($out));

        $stats = Media::stats();
        $this->components->info(sprintf(
            'Bundling %s files (%s)', number_format($stats['files']), $this->human($stats['bytes'])
        ));

        $exclude = $this->option('skip-pdf') ? "--exclude='*.pdf' " : '';

        // -C public so the archive extracts as media/... relative to public/
        $cmd = sprintf(
            'tar -czf %s %s-C %s %s',
            escapeshellarg($out), $exclude, escapeshellarg(public_path()), escapeshellarg(Media::DIR)
        );

        $this->line("  $ {$cmd}");
        exec($cmd, $output, $code);

        if ($code !== 0) {
            $this->error('tar failed with exit code '.$code);

            return self::FAILURE;
        }

        if ($this->option('manifest')) {
            $this->writeManifest($dir, $out.'.manifest.txt');
        }

        $this->newLine();
        $this->components->info('Archive ready: '.$out.' ('.$this->human(filesize($out)).')');
        $this->newLine();
        $this->line('  Upload and extract on the server:');
        $this->line('    scp '.basename($out).' user@host:/tmp/');
        $this->line('    ssh user@host "cd /path/to/site/public && tar -xzf /tmp/'.basename($out).'"');
        $this->line('    ssh user@host "cd /path/to/site && php artisan media:verify"');

        return self::SUCCESS;
    }

    private function writeManifest(string $dir, string $path): void
    {
        $lines = [];
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($it as $file) {
            if ($file->isFile()) {
                $rel = str_replace(public_path().DIRECTORY_SEPARATOR, '', $file->getPathname());
                $lines[] = md5_file($file->getPathname()).'  '.str_replace('\\', '/', $rel);
            }
        }

        sort($lines);
        File::put($path, implode(PHP_EOL, $lines));
        $this->line('  manifest: '.$path.' ('.count($lines).' files)');
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
