<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['available', 'booked'])->default('available');
            $table->decimal('price_override', 10, 2)->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['listing_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_availabilities');
    }
};
