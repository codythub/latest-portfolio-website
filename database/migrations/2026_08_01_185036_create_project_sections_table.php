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
        Schema::create('project_sections', function (Blueprint $table) {

            // Main ID for each section.
            $table->id();

            // Connect this section to one project.
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            // Example: Overview, The Problem, Research.
            $table->string('title');

            // The main written content for the section.
            $table->text('body')->nullable();

            // Controls the order of sections on the public page.
            $table->unsignedInteger('display_order')->default(0);

            // Controls whether visitors can see this section.
            $table->boolean('is_visible')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_sections');
    }
};
