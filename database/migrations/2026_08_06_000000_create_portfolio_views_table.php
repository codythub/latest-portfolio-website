<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_views', function (Blueprint $table) {
            $table->id();
            $table->string('route_name', 80);
            $table->string('page_type', 40);
            $table->string('path');
            $table->nullableMorphs('viewable');
            $table->char('visitor_hash', 64);
            $table->date('viewed_on')->index();
            $table->timestamp('viewed_at')->index();
            $table->timestamps();

            $table->index(['page_type', 'viewed_at']);
            $table->index(['visitor_hash', 'viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_views');
    }
};
