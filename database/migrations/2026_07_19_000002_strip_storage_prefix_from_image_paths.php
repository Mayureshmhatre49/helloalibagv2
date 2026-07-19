<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Older ImageService uploads stored the path with a leading "/storage/"
     * (e.g. "/storage/listings/55/x.webp"). Display code prepends
     * asset('storage/…'), producing a doubled "/storage//storage/…" URL that
     * 404s. Strip the prefix so every path is a clean relative disk path.
     */
    public function up(): void
    {
        foreach (['listing_images', 'review_photos', 'classified_images'] as $table) {
            if (! \Illuminate\Support\Facades\Schema::hasTable($table)) {
                continue;
            }

            DB::table($table)
                ->where('path', 'like', '/storage/%')
                ->update(['path' => DB::raw("SUBSTRING(path, 10)")]); // remove leading "/storage/" (9 chars)

            if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'thumbnail')) {
                DB::table($table)
                    ->where('thumbnail', 'like', '/storage/%')
                    ->update(['thumbnail' => DB::raw("SUBSTRING(thumbnail, 10)")]);
            }
        }
    }

    public function down(): void
    {
        // No safe reverse — the prefix was invalid data.
    }
};
