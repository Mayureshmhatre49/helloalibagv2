<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Daily counter per usage type (map_load / location_search), upserted
        // via INSERT ... ON DUPLICATE KEY UPDATE — same shape as
        // listing_view_logs — so this stays small (types x days) rather than
        // one row per API call.
        Schema::create('map_api_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->date('usage_date');
            $table->string('usage_type');
            $table->unsignedInteger('hits')->default(0);
            $table->timestamps();

            $table->unique(['usage_date', 'usage_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_api_usage_logs');
    }
};
