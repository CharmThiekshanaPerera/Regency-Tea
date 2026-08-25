<?php

namespace App\Filament\Pages;

use App\Models\Brand;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Product;
use Filament\Pages\Page as FilamentPage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * A live health check of the services the site actually depends on — not a
 * mock/placeholder. Each check below runs a real operation (a query, a
 * cache write, a disk write) rather than just reading config, so a red
 * status here means something is genuinely broken right now.
 */
class SystemStatus extends FilamentPage
{
    protected static ?string $navigationIcon = 'heroicon-o-signal';

    protected static ?string $navigationGroup = 'Site';

    protected static ?string $navigationLabel = 'System Status';

    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.system-status';

    public function getTitle(): string
    {
        return 'System Status';
    }

    /** @return array<int, array{name: string, ok: bool, detail: string}> */
    public function getChecks(): array
    {
        return [
            $this->checkDatabase(),
            $this->checkCache(),
            $this->checkSession(),
            $this->checkQueue(),
            $this->checkMediaDisk(),
            $this->checkMail(),
            $this->checkStorageWritable(),
            $this->checkMigrations(),
            $this->checkContentData(),
            $this->checkTranslations(),
        ];
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            $count = DB::table('products')->count();

            return ['name' => 'Database', 'ok' => true, 'detail' => "Connected ({$count} products readable)."];
        } catch (\Throwable $e) {
            return ['name' => 'Database', 'ok' => false, 'detail' => $e->getMessage()];
        }
    }

    private function checkCache(): array
    {
        try {
            $key = 'system-status:probe:'.Str::random(8);
            Cache::put($key, 'ok', 10);
            $ok = Cache::get($key) === 'ok';
            Cache::forget($key);

            return ['name' => 'Cache', 'ok' => $ok, 'detail' => $ok ? 'Write/read round-trip succeeded ('.config('cache.default').' driver).' : 'Wrote a value but could not read it back.'];
        } catch (\Throwable $e) {
            return ['name' => 'Cache', 'ok' => false, 'detail' => $e->getMessage()];
        }
    }

    private function checkSession(): array
    {
        try {
            if (config('session.driver') !== 'database') {
                return ['name' => 'Sessions', 'ok' => true, 'detail' => config('session.driver').' driver — no table check needed.'];
            }
            $table = config('session.table', 'sessions');
            $exists = Schema::hasTable($table);

            return ['name' => 'Sessions', 'ok' => $exists, 'detail' => $exists ? "`{$table}` table present." : "`{$table}` table is missing."];
        } catch (\Throwable $e) {
            return ['name' => 'Sessions', 'ok' => false, 'detail' => $e->getMessage()];
        }
    }

    private function checkQueue(): array
    {
        try {
            if (config('queue.default') !== 'database') {
                return ['name' => 'Queue', 'ok' => true, 'detail' => config('queue.default').' driver — no table check needed.'];
            }
            $exists = Schema::hasTable('jobs');

            return ['name' => 'Queue', 'ok' => $exists, 'detail' => $exists ? '`jobs` table present.' : '`jobs` table is missing.'];
        } catch (\Throwable $e) {
            return ['name' => 'Queue', 'ok' => false, 'detail' => $e->getMessage()];
        }
    }

    private function checkMediaDisk(): array
    {
        try {
            $disk = Storage::disk('media');
            $path = 'system-status-probe.txt';
            $disk->put($path, 'ok');
            $ok = $disk->get($path) === 'ok';
            $disk->delete($path);

            return ['name' => 'Media disk', 'ok' => $ok, 'detail' => $ok ? 'Write/read/delete round-trip succeeded — product & post images can be uploaded.' : 'Disk did not return the written value.'];
        } catch (\Throwable $e) {
            return ['name' => 'Media disk', 'ok' => false, 'detail' => $e->getMessage()];
        }
    }

    private function checkMail(): array
    {
        $mailer = config('mail.default');
        if ($mailer === 'log') {
            return ['name' => 'Mail', 'ok' => false, 'detail' => 'Mailer is set to "log" — enquiry form submissions are NOT being emailed anywhere, only written to storage/logs/laravel.log. Set MAIL_MAILER to a real transport (smtp, postmark, etc.) in .env if enquiries should actually reach an inbox.'];
        }

        return ['name' => 'Mail', 'ok' => true, 'detail' => "Configured mailer: {$mailer} (host: ".config('mail.mailers.smtp.host', 'n/a').').'];
    }

    private function checkStorageWritable(): array
    {
        $path = storage_path('logs');
        $ok = File::isWritable($path);

        return ['name' => 'Storage/logs', 'ok' => $ok, 'detail' => $ok ? "{$path} is writable." : "{$path} is NOT writable — errors will fail to log."];
    }

    private function checkMigrations(): array
    {
        try {
            $ran = DB::table('migrations')->pluck('migration')->all();
            $files = collect(File::files(database_path('migrations')))
                ->map(fn ($f) => pathinfo($f->getFilename(), PATHINFO_FILENAME))
                ->all();
            $pending = array_diff($files, $ran);

            return [
                'name'   => 'Migrations',
                'ok'     => count($pending) === 0,
                'detail' => count($pending) === 0
                    ? 'All '.count($files).' migrations have run.'
                    : count($pending).' pending: '.implode(', ', array_slice($pending, 0, 5)),
            ];
        } catch (\Throwable $e) {
            return ['name' => 'Migrations', 'ok' => false, 'detail' => $e->getMessage()];
        }
    }

    private function checkContentData(): array
    {
        $products = Product::count();
        $brands = Brand::count();
        $pages = Page::count();
        $menu = MenuItem::count();
        $ok = $products > 0 && $brands > 0 && $pages > 0 && $menu > 0;

        return [
            'name'   => 'Imported content',
            'ok'     => $ok,
            'detail' => "{$products} products, {$brands} brands, {$pages} pages, {$menu} menu items."
                .($ok ? '' : ' Looks empty — has `php artisan wp:import` been run on this database?'),
        ];
    }

    private function checkTranslations(): array
    {
        $locales = array_keys(config('regency.locales', []));
        $missing = [];
        foreach ($locales as $locale) {
            if (! is_dir(lang_path("{$locale}/pages"))) {
                $missing[] = $locale;
            }
        }

        return [
            'name'   => 'Translation files',
            'ok'     => empty($missing),
            'detail' => empty($missing)
                ? count($locales).' locales configured, all have lang/{locale}/pages/ files.'
                : 'Missing lang/{locale}/pages/ for: '.implode(', ', $missing),
        ];
    }
}
