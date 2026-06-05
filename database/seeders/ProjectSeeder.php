<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء 5 متطوعين
        $users = User::factory()->count(5)->create();

        // الحصول على جميع المهارات
        $skills = Skill::all();

        // إنشاء 8 مشاريع تطوعية
        Project::factory()->count(8)->sequence(
            ['user_id' => $users[0]->id],
            ['user_id' => $users[1]->id],
            ['user_id' => $users[2]->id],
            ['user_id' => $users[3]->id],
            ['user_id' => $users[4]->id],
            ['user_id' => $users[0]->id],
            ['user_id' => $users[1]->id],
            ['user_id' => $users[2]->id],
        )->create()->each(function (Project $project) use ($skills) {
            // ربط 2-4 مهارات عشوائية لكل مشروع
            $project->skills()->attach(
                $skills->random(rand(2, 4))->pluck('id')
            );
        });

        // ربط 3-5 مهارات عشوائية لكل متطوع
        $users->each(function (User $user) use ($skills) {
            $user->skills()->attach(
                $skills->random(rand(3, 5))->pluck('id')
            );
        });
    }
}
