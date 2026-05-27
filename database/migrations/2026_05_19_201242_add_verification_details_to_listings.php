<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->timestamp('verified_at')->nullable()->after('is_verified');
            $table->string('verification_note')->nullable()->after('verified_at');
            $table->foreignId('verified_by')->nullable()->after('verification_note')->constrained('users')->nullOnDelete();

            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropIndex(['is_verified']);
            $table->dropColumn(['verified_at', 'verification_note', 'verified_by']);
        });
    }
};
