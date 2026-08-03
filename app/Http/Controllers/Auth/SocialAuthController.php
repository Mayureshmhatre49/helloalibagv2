<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // Facebook is temporarily disabled — not configured yet. Add 'facebook' back
    // here once FACEBOOK_CLIENT_ID/SECRET are set and the login/register buttons
    // are restored.
    private const ENABLED_PROVIDERS = ['google'];

    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirectToProvider(Request $request, string $provider): RedirectResponse
    {
        if (!in_array($provider, self::ENABLED_PROVIDERS)) {
            abort(404);
        }

        if ($request->has('account_type')) {
            session(['social_account_type' => $request->account_type]);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider and login/register.
     */
    public function handleProviderCallback(Request $request, string $provider): RedirectResponse
    {
        if (!in_array($provider, self::ENABLED_PROVIDERS)) {
            return redirect()->route('login')->withErrors(['social' => 'Invalid social provider.']);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['social' => 'Unable to authenticate with ' . ucfirst($provider) . '. Please try again.']);
        }

        $providerIdField = $provider . '_id';

        // 1. Check if existing user has this social ID
        $user = User::where($providerIdField, $socialUser->getId())->first();

        if (!$user && $socialUser->getEmail()) {
            // 2. Check if user exists by email address
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Link existing user account to social provider ID
                $user->update([
                    $providerIdField => $socialUser->getId(),
                ]);
            }
        }

        if (!$user) {
            // 3. Create new user via social auth
            $accountType = session()->pull('social_account_type', 'user');
            $roleSlug = $accountType === 'owner' ? 'owner' : 'user';
            $role = Role::where('slug', $roleSlug)->first();

            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                'role_id' => $role?->id,
                $providerIdField => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);

        if ($user->isOwner()) {
            return redirect()->route('owner.onboarding.start');
        }

        return redirect()->route('home');
    }
}
