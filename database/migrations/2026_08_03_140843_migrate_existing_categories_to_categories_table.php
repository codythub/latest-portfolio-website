<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Move existing blog categories into the categories table.
        $postCategories = DB::table('posts')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        foreach ($postCategories as $categoryName) {
            $categoryId = DB::table('categories')->insertGetId([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'type' => 'blog',
                'is_visible' => true,
                'display_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('posts')
                ->where('category', $categoryName)
                ->update([
                    'category_id' => $categoryId,
                ]);
        }
    }

    public function down(): void
    {
        // Keep the migrated records when rolling back.
        // The old category text columns still contain their original values.
    }
};