<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('categories', 'project_classification')) {
            return;
        }

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

        DB::table('categories')
            ->where('type', Category::TYPE_PROJECT)
            ->where(function ($query) {
                $query
                    ->whereNull('project_classification')
                    ->orWhereNotIn('project_classification', [
                        Category::PROJECT_CLASSIFICATION_DESIGN,
                        Category::PROJECT_CLASSIFICATION_DEVELOPMENT,
                    ]);
            })
            ->update([
                'project_classification' => Category::PROJECT_CLASSIFICATION_DEVELOPMENT,
            ]);
    }

    public function down(): void
    {
        if (! Schema::hasColumn('categories', 'project_classification')) {
            return;
        }

        DB::table('categories')
            ->where('type', Category::TYPE_PROJECT)
            ->where(function ($query) {
                $query
                    ->whereRaw('LOWER(name) LIKE ?', ['%design%'])
                    ->orWhereRaw('LOWER(slug) LIKE ?', ['%design%']);
            })
            ->update([
                'project_classification' => Category::PROJECT_CLASSIFICATION_DEVELOPMENT,
            ]);
    }
};
