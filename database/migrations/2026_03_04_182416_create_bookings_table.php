<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->unsignedTinyInteger('guests')->default(1);
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'declined', 'completed', 'cancelled'])
                  ->default('pending');
            $table->decimal('total_price', 10, 2)->nullable();
            $table->text('owner_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index(['listing_id', 'status']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
