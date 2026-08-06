<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('site_settings', 'hero_background_image')) {
                $table->string('hero_background_image')->nullable()->after('id');
            }
        });

        DB::table('site_settings')
            ->select('id', 'home_hero_background_image', 'contact_hero_background_image')
            ->orderBy('id')
            ->get()
            ->each(function ($settings): void {
                DB::table('site_settings')
                    ->where('id', $settings->id)
                    ->update([
                        'hero_background_image' => $settings->home_hero_background_image
                            ?: $settings->contact_hero_background_image,
                    ]);
            });

        Schema::table('site_settings', function (Blueprint $table) {
            if (Schema::hasColumn('site_settings', 'home_hero_background_image')) {
                $table->dropColumn('home_hero_background_image');
            }

            if (Schema::hasColumn('site_settings', 'contact_hero_background_image')) {
                $table->dropColumn('contact_hero_background_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('site_settings', 'home_hero_background_image')) {
                $table->string('home_hero_background_image')->nullable()->after('id');
            }

            if (! Schema::hasColumn('site_settings', 'contact_hero_background_image')) {
                $table->string('contact_hero_background_image')->nullable()->after('about_background_image');
            }
        });

        DB::table('site_settings')
            ->select('id', 'hero_background_image')
            ->orderBy('id')
            ->get()
            ->each(function ($settings): void {
                DB::table('site_settings')
                    ->where('id', $settings->id)
                    ->update([
                        'home_hero_background_image' => $settings->hero_background_image,
                        'contact_hero_background_image' => $settings->hero_background_image,
                    ]);
            });

        Schema::table('site_settings', function (Blueprint $table) {
            if (Schema::hasColumn('site_settings', 'hero_background_image')) {
                $table->dropColumn('hero_background_image');
            }
        });
    }
};
