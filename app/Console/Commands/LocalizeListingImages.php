<?php

namespace App\Console\Commands;

use App\Models\ListingImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalizeListingImages extends Command
{
    protected $signature = 'listings:localize-images
        {--dry-run : Report what would change without downloading anything}
        {--limit=0 : Only process this many images (0 = all)}';

    protected $description = 'Download externally-hotlinked listing images into local public storage and repoint the DB path.';

    private const ALLOWED_EXT = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $limit = (int) $this->option('limit');

        $query = ListingImage::query()
            ->where(fn ($q) => $q->where('path', 'like', 'http://%')->orWhere('path', 'like', 'https://%'))
            ->orderBy('id');

        if ($limit > 0) {
            $query->limit($limit);
        }

        $images = $query->get();

        if ($images->isEmpty()) {
            $this->info('No externally-hosted listing images found — nothing to localize.');
            return self::SUCCESS;
        }

        $this->info(($dryRun ? '[DRY RUN] ' : '') . "Found {$images->count()} external image(s) to localize.");

        $ok = 0;
        $failed = 0;

        foreach ($images as $image) {
            $url = $image->path;

            if ($dryRun) {
                $this->line("  would download #{$image->id}: {$url}");
                continue;
            }

            try {
                $response = Http::timeout(20)
                    ->withHeaders(['User-Agent' => 'HelloAlibaug/1.0 (+https://helloalibaug.com)'])
                    ->retry(2, 500)
                    ->get($url);

                if (! $response->successful()) {
                    $this->warn("  ✗ #{$image->id} HTTP {$response->status()} — {$url}");
                    $failed++;
                    continue;
                }

                $ext = $this->extensionFor($url, $response->header('Content-Type'));
                $dir = 'listings/' . $image->listing_id;
                $filename = $dir . '/' . Str::random(20) . '.' . $ext;

                Storage::disk('public')->put($filename, $response->body());

                $image->update(['path' => $filename]);
                $this->line("  ✓ #{$image->id} → storage/{$filename}");
                $ok++;
            } catch (\Throwable $e) {
                $this->warn("  ✗ #{$image->id} {$e->getMessage()} — {$url}");
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Done. Localized: {$ok}, failed: {$failed}.");

        if ($ok > 0) {
            $this->call('cache:forget', ['key' => 'map.markers.approved']);
        }

        return $failed > 0 && $ok === 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Pick a safe file extension from the URL, falling back to the response Content-Type.
     */
    private function extensionFor(string $url, ?string $contentType): string
    {
        $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
        if (in_array($ext, self::ALLOWED_EXT, true)) {
            return $ext === 'jpeg' ? 'jpg' : $ext;
        }

        $map = [
            'image/jpeg' => 'jpg', 'image/jpg' => 'jpg', 'image/png' => 'png',
            'image/webp' => 'webp', 'image/gif' => 'gif', 'image/avif' => 'avif',
        ];
        $type = strtolower(trim(explode(';', (string) $contentType)[0]));

        return $map[$type] ?? 'jpg';
    }
}
