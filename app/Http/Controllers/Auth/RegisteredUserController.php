<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Mail\NewUserRegistered;
use App\Models\UserNotification;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'account_type' => ['required', 'in:user,owner'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cf-turnstile-response' => [\Illuminate\Validation\Rule::requiredIf(fn () => filled(config('services.turnstile.secret_key'))), new \App\Rules\Captcha()],
        ]);

        // Assign role based on account type
        $roleSlug = $request->account_type === 'owner' ? 'owner' : 'user';
        $role = Role::where('slug', $roleSlug)->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $role?->id,
        ]);

        event(new Registered($user));

        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Welcome email failed to send: ' . $e->getMessage());
        }

        $admins = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->get();
        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new NewUserRegistered($user));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Admin new-registration email failed: ' . $e->getMessage());
            }

            try {
                UserNotification::create([
                    'user_id' => $admin->id,
                    'type' => 'user_registered',
                    'title' => 'New Registration',
                    'message' => $user->name . ' (' . $user->email . ') signed up as ' . ($role?->name ?? 'a user') . '.',
                    'data' => ['user_id' => $user->id],
                    'action_url' => route('admin.users.index', ['search' => $user->email]),
                ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Admin new-registration notification failed: ' . $e->getMessage());
            }
        }

        Auth::login($user);

        // Always send new users to the plans page first
        return redirect()->route('subscription.plans')
            ->with('success', 'Welcome! Choose a plan to get started.');
    }
}
