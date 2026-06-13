<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Exceptions\InvalidStateException;
use Laravel\Socialite\Facades\Socialite;

class LinkedInController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('linkedin')
            ->scopes(['openid', 'profile', 'email', 'w_member_social'])
            ->redirectUrl(config('services.linkedin.redirect'))
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $socialiteUser = Socialite::driver('linkedin')
                ->redirectUrl(config('services.linkedin.redirect'))
                ->user();
        } catch (InvalidStateException $e) {
            // User cancelled LinkedIn auth or session expired
            Log::warning('LinkedIn OAuth cancelled or state mismatch', [
                'error' => $e->getMessage(),
            ]);

            // Clear the redirect flag if it exists
            session()->forget('redirect_after_linkedin');

            return redirect()->route('badge.show')
                ->with('error', 'تم إلغاء التوصيل. يرجى المحاولة مرة أخرى.');
        }

        $linkedinId = $socialiteUser->getId();
        $accessToken = $socialiteUser->token;
        $expiresIn = $socialiteUser->expiresIn ?? 3600;

        Log::info('LinkedIn callback received', [
            'linkedin_id' => $linkedinId,
            'token_length' => strlen($accessToken),
            'expires_in' => $expiresIn,
            'socialite_data' => $socialiteUser->getRaw(),
        ]);

        $profileData = $this->fetchExtendedProfile($accessToken);

        $isNewUser = ! User::where('linkedin_id', $linkedinId)->exists();

        $user = User::updateOrCreate(
            ['linkedin_id' => $linkedinId],
            [
                'name' => $socialiteUser->getName(),
                'email' => $socialiteUser->getEmail(),
                'avatar_url' => $socialiteUser->getAvatar(),
                'linkedin_access_token' => $accessToken,
                'linkedin_token_expires_at' => now()->addSeconds($expiresIn),
                'headline' => $profileData['headline'] ?? null,
                'linkedin_profile_url' => $profileData['profileUrl'] ?? null,
            ]
        );

        Auth::login($user, true);

        // Check if there's a redirect after LinkedIn callback
        $redirectAfter = session()->pull('redirect_after_linkedin');
        if ($redirectAfter) {
            return redirect()->route($redirectAfter);
        }

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
