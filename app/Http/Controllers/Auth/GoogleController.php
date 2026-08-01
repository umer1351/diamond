<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Self-contained "Continue with Google" sign-in (no external package).
 *
 * It stays dormant until GOOGLE_CLIENT_ID / GOOGLE_CLIENT_SECRET are set in
 * .env. Configure the OAuth client in Google Cloud Console and set the
 * authorized redirect URI to  {APP_URL}/auth/google/callback.
 */
class GoogleController extends Controller
{
    private function configured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'));
    }

    private function redirectUri(): string
    {
        return config('services.google.redirect') ?: route('google.callback');
    }

    public function redirect(Request $request): RedirectResponse
    {
        if (! $this->configured()) {
            return redirect()->route('login')->withErrors([
                'login' => 'Google sign-in is not configured yet. Add GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in the .env file.',
            ]);
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $params = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => $this->redirectUri(),
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'state' => $state,
            'access_type' => 'online',
            'prompt' => 'select_account',
        ]);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?' . $params);
    }

    public function callback(Request $request): RedirectResponse
    {
        if (! $this->configured()) {
            return redirect()->route('login')->withErrors(['login' => 'Google sign-in is not configured.']);
        }

        if ($request->filled('error')) {
            return redirect()->route('login')->withErrors(['login' => 'Google sign-in was cancelled.']);
        }

        $expectedState = $request->session()->pull('google_oauth_state');
        if (! $expectedState || ! hash_equals($expectedState, (string) $request->input('state'))) {
            return redirect()->route('login')->withErrors(['login' => 'Google sign-in failed (invalid state). Please try again.']);
        }

        $code = (string) $request->input('code');
        if ($code === '') {
            return redirect()->route('login')->withErrors(['login' => 'Google sign-in failed (missing code).']);
        }

        try {
            $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => $this->redirectUri(),
                'grant_type' => 'authorization_code',
            ]);

            $accessToken = $tokenResponse->json('access_token');
            if (! $accessToken) {
                Log::warning('Google token exchange failed', ['body' => $tokenResponse->body()]);
                return redirect()->route('login')->withErrors(['login' => 'Google sign-in failed. Please try again.']);
            }

            $profile = Http::withToken($accessToken)
                ->get('https://www.googleapis.com/oauth2/v3/userinfo')
                ->json();
        } catch (\Throwable $e) {
            Log::error('Google sign-in error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['login' => 'Google sign-in is temporarily unavailable.']);
        }

        $googleId = $profile['sub'] ?? null;
        $email = $profile['email'] ?? null;

        if (! $googleId || ! $email) {
            return redirect()->route('login')->withErrors(['login' => 'Google did not return an email address.']);
        }

        $user = User::where('google_id', $googleId)->first()
            ?? User::where('email', $email)->first();

        if ($user) {
            $user->google_id = $user->google_id ?: $googleId;
            if (empty($user->avatar) && ! empty($profile['picture'])) {
                $user->avatar = $profile['picture'];
            }
            $user->save();
        } else {
            $user = User::create([
                'name' => $profile['name'] ?? Str::before($email, '@'),
                'email' => $email,
                'google_id' => $googleId,
                'avatar' => $profile['picture'] ?? null,
                'email_verified_at' => now(),
                // Random password so the NOT NULL column is satisfied; the user
                // signs in with Google, not this password.
                'password' => Hash::make(Str::random(40)),
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('storefront.index'));
    }
}
