<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class LinkedInController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('linkedin')
            ->scopes(['openid', 'profile', 'email', 'w_member_social'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        $socialiteUser = Socialite::driver('linkedin')->user();

        $linkedinId = $socialiteUser->getId();
        $accessToken = $socialiteUser->token;

        $profileData = $this->fetchExtendedProfile($accessToken);

        $isNewUser = ! User::where('linkedin_id', $linkedinId)->exists();

        $user = User::updateOrCreate(
            ['linkedin_id' => $linkedinId],
            [
                'name' => $socialiteUser->getName(),
                'email' => $socialiteUser->getEmail(),
                'avatar_url' => $socialiteUser->getAvatar(),
                'linkedin_access_token' => $accessToken,
                'linkedin_token_expires_at' => now()->addSeconds($socialiteUser->expiresIn ?? 3600),
                'headline' => $profileData['headline'] ?? null,
                'linkedin_profile_url' => $profileData['profileUrl'] ?? null,
            ]
        );

        Auth::login($user, true);

        if ($isNewUser) {
            return redirect()->route('badge.show');
        }

        return redirect()->intended(route('home'));
    }

    /**
     * @return array{headline: ?string, profileUrl: ?string}
     */
    private function fetchExtendedProfile(string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->get('https://api.linkedin.com/v2/me', [
                'projection' => '(id,localizedHeadline,vanityName)',
            ]);

        if (! $response->ok()) {
            return ['headline' => null, 'profileUrl' => null];
        }

        $data = $response->json();

        return [
            'headline' => $data['localizedHeadline'] ?? null,
            'profileUrl' => isset($data['vanityName'])
                ? 'https://www.linkedin.com/in/'.$data['vanityName']
                : null,
        ];
    }
}
