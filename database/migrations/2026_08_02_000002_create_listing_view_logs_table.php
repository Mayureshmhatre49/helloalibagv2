<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Daily per-listing view aggregate. One row per listing per day keeps
        // this table small (listings x days) rather than one row per hit, while
        // still giving the owner dashboard a real 30-day trend.
        Schema::create('listing_view_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('listings')->cascadeOnDelete();
            $table->date('viewed_on');
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            // Enables the atomic INSERT ... ON DUPLICATE KEY UPDATE upsert and
            // covers the dashboard's "my listings, last 30 days" range scan.
            $table->unique(['listing_id', 'viewed_on']);
            $table->index('viewed_on');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_view_logs');
    }
};
