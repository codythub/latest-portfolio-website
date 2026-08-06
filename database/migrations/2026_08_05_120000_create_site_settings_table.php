<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('home_hero_background_image')->nullable();
            $table->string('about_background_image')->nullable();
            $table->string('contact_hero_background_image')->nullable();
            $table->string('footer_credit_text')->default(SiteSetting::DEFAULT_FOOTER_CREDIT_TEXT);
            $table->string('contact_heading')->default(SiteSetting::DEFAULT_CONTACT_HEADING);
            $table->text('contact_description')->nullable();
            $table->string('default_seo_title')->default(SiteSetting::DEFAULT_SEO_TITLE);
            $table->text('default_meta_description')->nullable();
            $table->string('favicon_path')->nullable();
            $table->timestamps();
        });

        DB::table('site_settings')->insert([
            'footer_credit_text' => SiteSetting::DEFAULT_FOOTER_CREDIT_TEXT,
            'contact_heading' => SiteSetting::DEFAULT_CONTACT_HEADING,
            'contact_description' => SiteSetting::DEFAULT_CONTACT_DESCRIPTION,
            'default_seo_title' => SiteSetting::DEFAULT_SEO_TITLE,
            'default_meta_description' => SiteSetting::DEFAULT_META_DESCRIPTION,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
