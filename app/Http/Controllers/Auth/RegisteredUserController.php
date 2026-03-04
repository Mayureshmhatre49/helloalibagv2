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

        Auth::login($user);

        // Always send new users to the plans page first
        return redirect()->route('subscription.plans')
            ->with('success', 'Welcome! Choose a plan to get started.');
    }
}
