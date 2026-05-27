<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->timestamp('event_start_at')->nullable()->after('approved_at');
            $table->timestamp('event_end_at')->nullable()->after('event_start_at');
            $table->boolean('event_is_recurring')->default(false)->after('event_end_at');

            $table->index('event_start_at');
            $table->index(['category_id', 'event_start_at']);
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropIndex(['event_start_at']);
            $table->dropIndex(['category_id', 'event_start_at']);
            $table->dropColumn(['event_start_at', 'event_end_at', 'event_is_recurring']);
        });
    }
};
