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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Category name shown in the admin area and public filters.
            $table->string('name');

            // URL-friendly version of the category name.
            $table->string('slug');

            // Separates project categories from blog categories.
            $table->string('type');

            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('display_order')->default(0);

            $table->timestamps();

            // Prevent duplicate categories within the same category type.
            $table->unique(['slug', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
