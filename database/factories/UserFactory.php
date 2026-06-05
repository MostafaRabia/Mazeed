<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arabicNames = ['أحمد محمد', 'فاطمة علي', 'محمود سالم', 'نور حسن', 'سارة إبراهيم', 'عمر خالد', 'ليلى يوسف', 'خالد محمود'];
        $arabicHeadlines = ['متطوع متحمس', 'مطور ويب', 'معلم تكنولوجيا', 'خبير تسويق رقمي', 'مصمم جرافيك', 'كاتب محتوى'];
        $arabicBios = ['أحب المساهمة في المجتمع', 'متطوع بشغف وإخلاص', 'مهتم بالعمل الخيري', 'أؤمن بقوة العمل الجماعي', 'مكرس لخدمة المجتمع'];

        return [
            'name' => fake()->randomElement($arabicNames),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'linkedin_id' => (string) fake()->unique()->numberBetween(1000000000, 9999999999),
            'linkedin_access_token' => Str::random(100),
            'avatar_url' => fake()->imageUrl(200, 200, 'people', true),
            'headline' => fake()->randomElement($arabicHeadlines),
            'bio' => fake()->randomElement($arabicBios),
            'linkedin_profile_url' => fake()->url(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
