<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Singleton row (fetched via MapApiSetting::first()) holding the
        // admin-managed Google Maps API key and its safety limits. DB-backed
        // rather than .env so the admin can toggle/edit it from the dashboard
        // without a redeploy.
        Schema::create('map_api_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->text('api_key')->nullable();
            $table->string('map_id')->nullable();
            $table->unsignedInteger('monthly_free_limit_map_loads')->default(10000);
            $table->unsignedInteger('monthly_free_limit_search')->default(10000);
            $table->unsignedTinyInteger('auto_disable_threshold_percent')->default(95);
            $table->timestamp('auto_disabled_at')->nullable();
            $table->string('auto_disabled_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_api_settings');
    }
};
