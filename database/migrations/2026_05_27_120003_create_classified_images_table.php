<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classified_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classified_id')->constrained('classifieds')->cascadeOnDelete();
            $table->text('path');
            $table->string('alt_text')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index('classified_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classified_images');
    }
};
