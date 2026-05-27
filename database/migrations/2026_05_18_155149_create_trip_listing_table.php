<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_listing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->string('note', 500)->nullable();
            $table->timestamps();

            $table->unique(['trip_id', 'listing_id']);
            $table->index(['trip_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_listing');
    }
};
