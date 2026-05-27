<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('share_token', 32)->unique();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('party_size')->default(2);
            $table->text('notes')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'slug']);
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
