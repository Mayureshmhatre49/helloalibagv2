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
        Schema::create('category_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('field_type'); // e.g., 'number', 'text', 'select', 'checkbox'
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('category_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_attribute_id')->constrained('category_attributes')->cascadeOnDelete();
            $table->string('value');
            $table->string('label');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('listing_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('listings')->cascadeOnDelete();
            $table->foreignId('category_attribute_id')->constrained('category_attributes')->cascadeOnDelete();
            $table->text('value');
            $table->timestamps();
            
            $table->unique(['listing_id', 'category_attribute_id'], 'listing_cat_attr_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing_attribute_values');
        Schema::dropIfExists('category_attribute_values');
        Schema::dropIfExists('category_attributes');
    }
};
