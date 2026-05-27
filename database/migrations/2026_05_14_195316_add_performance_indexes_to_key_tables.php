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
        // Composite index for the very common "approved + featured" home-page query
        Schema::table('listings', function (Blueprint $table) {
            $table->index(['status', 'is_featured'], 'listings_status_featured_index');
        });

        // Composite index for the "published posts ordered by date" blog index query
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->index('status', 'blog_posts_status_index');
            $table->index(['status', 'published_at'], 'blog_posts_status_published_at_index');
        });

        // Speed up email-verification gate
        Schema::table('users', function (Blueprint $table) {
            $table->index('email_verified_at', 'users_email_verified_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropIndex('listings_status_featured_index');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex('blog_posts_status_index');
            $table->dropIndex('blog_posts_status_published_at_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_email_verified_at_index');
        });
    }
};
