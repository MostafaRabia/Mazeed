<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            'Web Development',
            'Mobile Development',
            'UI/UX Design',
            'Graphic Design',
            'Data Science',
            'Machine Learning',
            'DevOps',
            'Project Management',
            'Marketing',
            'Content Writing',
            'Video Editing',
            'Photography',
            'Translation',
            'Business Development',
            'Community Management',
        ];

        foreach ($skills as $name) {
            Skill::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
