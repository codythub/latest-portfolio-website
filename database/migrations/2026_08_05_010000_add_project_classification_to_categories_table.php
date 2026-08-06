<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table
                ->string('project_classification')
                ->nullable()
                ->after('type');
        });

        DB::table('categories')
            ->where('type', Category::TYPE_PROJECT)
            ->update([
                'project_classification' => Category::PROJECT_CLASSIFICATION_DEVELOPMENT,
            ]);

        DB::table('categories')
            ->where('type', Category::TYPE_PROJECT)
            ->where(function ($query) {
                $query
                    ->whereRaw('LOWER(name) LIKE ?', ['%design%'])
                    ->orWhereRaw('LOWER(slug) LIKE ?', ['%design%']);
            })
            ->update([
                'project_classification' => Category::PROJECT_CLASSIFICATION_DESIGN,
            ]);
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('project_classification');
        });
    }
};
