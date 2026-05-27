<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classifieds', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->boolean('is_negotiable')->default(false);
            $table->enum('condition', ['new', 'like_new', 'good', 'fair'])->nullable();

            $table->foreignId('classified_category_id')->constrained('classified_categories')->cascadeOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();

            // Moderated lifecycle: pending -> active -> sold/expired (or rejected)
            $table->enum('status', ['pending', 'active', 'sold', 'expired', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            // Contact (platform only connects buyer & seller — no payment handling)
            $table->string('contact_phone')->nullable();
            $table->string('contact_whatsapp')->nullable();

            $table->unsignedBigInteger('views_count')->default(0);

            // Hooks for future paid boosts
            $table->boolean('is_featured')->default(false);
            $table->timestamp('featured_until')->nullable();

            $table->timestamp('sold_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            $table->fullText(['title', 'description']);
            $table->index('status');
            $table->index('classified_category_id');
            $table->index('price');
            $table->index('is_featured');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classifieds');
    }
};
