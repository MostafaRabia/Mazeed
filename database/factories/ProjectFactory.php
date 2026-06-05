<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arabicTitles = [
            'تطوير منصة تعليمية رقمية',
            'مساعدة الأطفال في الدراسة',
            'تنظيم حملة توعية صحية',
            'بناء مكتبة رقمية مجانية',
            'تدريب الشباب على البرمجة',
            'مشروع تنظيف البيئة',
            'محو الأمية الرقمية',
            'دعم الفنانين المحليين',
            'برنامج التدريب الوظيفي',
            'مشروع دعم ريادة الأعمال',
        ];

        $arabicDescriptions = [
            'نبحث عن متطوعين متحمسين للانضمام إلى مشروعنا الهام والمساهمة في خدمة المجتمع بكل إخلاص وتفاني.',
            'هذا المشروع يهدف إلى تحسين حياة الناس في المجتمع من خلال العمل الجماعي والتعاون البناء.',
            'نسعى للبحث عن متطوعين لديهم شغف بالعمل الخيري والرغبة في إحداث فرق إيجابي في مجتمعهم.',
            'المشروع يتطلب تفاني والتزام من متطوعين يؤمنون بأهمية الخدمة الاجتماعية والعطاء.',
            'نرحب بكل من يرغب في المساهمة في بناء مستقبل أفضل لنا جميعاً من خلال التطوع والعمل المشترك.',
        ];

        $title = $this->faker->randomElement($arabicTitles);

        return [
            'title' => $title,
            'slug' => Str::slug($title, language: 'ar') . '-' . fake()->unique()->numerify('###'),
            'description' => $this->faker->randomElement($arabicDescriptions),
            'contact_info' => 'contact@example.com',
            'image' => null,
            'status' => 'active',
        ];
    }
}
