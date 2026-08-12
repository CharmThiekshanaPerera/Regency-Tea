<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Resolves stored media paths to public URLs.
 *
 * Media lives in `public/media/<year>/<month>/<file>` — mirroring the original
 * WordPress `wp-content/uploads` structure — and is served directly by the web
 * server.
 *
 * This deliberately does NOT use Storage::url()/the `public` disk, because that
 * requires `php artisan storage:link` and a document root that follows the
 * symlink. On shared/cPanel hosting that link is frequently missing or not
 * followed, which silently breaks every image on the site. Serving from
 * `public/` removes the failure mode entirely and lets images be rsync'd
 * straight to the server.
 */
class Media
{
    public const DIR = 'media';

    /** Public URL for a stored path, or null when there is nothing to show. */
    public static function url(?string $path): ?string
    {
        $path = self::normalise($path);

        if ($path === null) {
            return null;
        }

        return asset(self::DIR.'/'.$path);
    }

    /** URL with a placeholder fallback, for templates that must render something. */
    public static function urlOrPlaceholder(?string $path): string
    {
        return self::url($path) ?? asset('images/placeholder.svg');
    }

    /** Absolute filesystem path, for existence checks and imports. */
    public static function path(?string $path): ?string
    {
        $path = self::normalise($path);

        return $path === null ? null : public_path(self::DIR.'/'.$path);
    }

    public static function exists(?string $path): bool
    {
        $full = self::path($path);

        return $full !== null && is_file($full);
    }

    /**
     * Accepts any of the shapes we have historically stored and returns a clean
     * relative path such as "2023/12/EAT-100TB.png".
     *
     *   "2023/12/x.png"              -> 2023/12/x.png
     *   "media/2023/12/x.png"        -> 2023/12/x.png   (legacy rows)
     *   "/storage/media/2023/12/x"   -> 2023/12/x       (pre-refactor rows)
     *   "https://old.site/wp-content/uploads/2023/12/x" -> 2023/12/x
     */
    public static function normalise(?string $path): ?string
    {
        if ($path === null) {
            return null;
        }

        $path = trim(str_replace('\\', '/', $path));

        if ($path === '') {
            return null;
        }

        // Strip an absolute URL down to its uploads-relative part.
        if (preg_match('#wp-content/uploads/(.+)$#i', $path, $m)) {
            $path = $m[1];
        }

        $path = preg_replace('#^https?://[^/]+/#i', '', $path);
        $path = preg_replace('#/+#', '/', $path);   // collapse // from Windows paths
        $path = ltrim($path, '/');
        $path = preg_replace('#^storage/#', '', $path);
        $path = preg_replace('#^'.preg_quote(self::DIR, '#').'/#', '', $path);

        // Refuse to escape the media directory.
        if (str_contains($path, '..')) {
            return null;
        }

        return $path === '' ? null : $path;
    }

    /**
     * Absolute URL — required for OpenGraph tags and JSON-LD, which reject
     * relative paths.
     */
    public static function absoluteUrl(?string $path): ?string
    {
        $url = self::url($path);

        if ($url === null) {
            return null;
        }

        return str_starts_with($url, 'http') ? $url : url($url);
    }

    /** Directory sizes, used by the media:bundle and wp:media reports. */
    public static function stats(): array
    {
        return Cache::remember('media:stats', 300, function () {
            $dir = public_path(self::DIR);

            if (! is_dir($dir)) {
                return ['files' => 0, 'bytes' => 0];
            }

            $files = 0;
            $bytes = 0;

            $it = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($it as $file) {
                if ($file->isFile()) {
                    $files++;
                    $bytes += $file->getSize();
                }
            }

            return ['files' => $files, 'bytes' => $bytes];
        });
    }
}
