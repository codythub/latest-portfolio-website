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
        Schema::create('projects', function (Blueprint $table) {

            // Laravel creates the main ID for every project
            $table->id();

            // The project name, for example SilentCare
            $table->string('title');

            // This will be used inside the project URL
            // Example: silentcare
            $table->string('slug')->unique();

            // This tells us whether it is a design or development project
            $table->string('project_type');

            // A short line under the project title
            $table->string('subtitle')->nullable();

            // The short description shown on the homepage
            $table->text('summary')->nullable();

            // Information shown on the case-study page
            $table->string('role')->nullable();
            $table->string('timeline')->nullable();
            $table->string('industry')->nullable();
            $table->string('year')->nullable();

            // The label changes depending on the project
            // Example: Focus Areas or Tech Stack
            $table->string('tag_label')->nullable();

            // These are the tags themselves
            // We will save them as a list
            $table->json('tags')->nullable();

            // Images used on the homepage and project page
            $table->string('thumbnail')->nullable();
            $table->string('cover_image')->nullable();

            // External project link
            // Example: Behance, GitHub or live website
            $table->string('external_link_label')->nullable();
            $table->string('external_link_url')->nullable();

            // This controls whether visitors can see the project
            $table->boolean('is_published')->default(false);

            // This controls the order projects appear in
            $table->unsignedInteger('display_order')->default(0);

            // Laravel automatically records when the project was created or updated
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
