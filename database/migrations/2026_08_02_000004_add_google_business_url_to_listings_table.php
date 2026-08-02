<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            // Google Business Profile link, so visitors can jump straight to the
            // business's Google listing for reviews, photos and directions.
            $table->string('google_business_url')->nullable()->after('website');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('google_business_url');
        });
    }
};
