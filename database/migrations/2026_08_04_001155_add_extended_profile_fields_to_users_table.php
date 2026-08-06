<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('professional_title')
                ->nullable()
                ->after('email');

            $table->text('about_intro')
                ->nullable()
                ->after('bio');

            $table->string('location')
                ->nullable()
                ->after('avatar');

            $table->string('whatsapp')
                ->nullable()
                ->after('location');

            $table->string('linkedin_url')
                ->nullable()
                ->after('whatsapp');

            $table->string('twitter_url')
                ->nullable()
                ->after('linkedin_url');

            $table->string('github_url')
                ->nullable()
                ->after('twitter_url');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'professional_title',
                'about_intro',
                'location',
                'whatsapp',
                'linkedin_url',
                'twitter_url',
                'github_url',
            ]);
        });
    }
};