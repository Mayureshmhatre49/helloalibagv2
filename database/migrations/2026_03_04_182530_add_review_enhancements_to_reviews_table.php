<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('owner_reply')->nullable()->after('comment');
            $table->timestamp('owner_replied_at')->nullable()->after('owner_reply');
            $table->unsignedInteger('helpful_count')->default(0)->after('owner_replied_at');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['owner_reply', 'owner_replied_at', 'helpful_count']);
        });
    }
};
