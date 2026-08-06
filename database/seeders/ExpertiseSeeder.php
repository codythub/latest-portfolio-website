<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Tool;
use Illuminate\Database\Seeder;

class ExpertiseSeeder extends Seeder
{
    /**
     * Seed the default tools and skills shown on the homepage.
     */
    public function run(): void
    {
        $tools = [
            ['name' => 'Figma', 'icon_key' => 'figma'],
            ['name' => 'Vue Js', 'icon_key' => 'vue'],
            ['name' => 'Laravel', 'icon_key' => 'laravel'],
            ['name' => 'Git', 'icon_key' => 'git'],
            ['name' => 'Postman', 'icon_key' => 'postman'],
        ];

        foreach ($tools as $index => $tool) {
            Tool::query()->updateOrCreate(
                ['name' => $tool['name']],
                [
                    'icon_key' => $tool['icon_key'],
                    'display_order' => $index,
                    'is_visible' => true,
                ]
            );
        }

        $skills = [
            'UI/UX Design',
            'REST API Development',
            'Laravel Development',
            'Database Design',
            'CRUD Application Development',
            'Authentication & Authorization',
        ];

        foreach ($skills as $index => $skill) {
            Skill::query()->updateOrCreate(
                ['name' => $skill],
                [
                    'display_order' => $index,
                    'is_visible' => true,
                ]
            );
        }
    }
}
