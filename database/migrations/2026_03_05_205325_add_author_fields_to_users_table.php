<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('author_slug')->unique()->nullable()->after('email');
            $table->text('author_bio')->nullable()->after('author_slug');
            $table->string('author_avatar')->nullable()->after('author_bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['author_slug', 'author_bio', 'author_avatar']);
        });
    }
};
