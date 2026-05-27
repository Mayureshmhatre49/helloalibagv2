<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guide_listing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_id')->constrained()->cascadeOnDelete();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->string('blurb', 500)->nullable();
            $table->timestamps();

            $table->unique(['guide_id', 'listing_id']);
            $table->index(['guide_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guide_listing');
    }
};
