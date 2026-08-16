<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Category;
use App\Models\Listing;
use App\Models\PlanInterest;
use App\Models\Role;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    /**
     * Show the plans page.
     */
    public function plans(Request $request): View
    {
        $user         = $request->user();
        $subscription = $user?->subscription;
        $plans        = Subscription::$plans;

        $interestedPlans = $user
            ? PlanInterest::where('user_id', $user->id)->pluck('plan')->all()
            : [];

        // Real platform numbers — used as trust signals on the pricing page.
        $stats = Cache::remember('plans.stats', now()->addMinutes(10), fn () => [
            'listings'   => Listing::where('status', 'approved')->count(),
            'areas'      => Area::where('is_active', true)->count(),
            'categories' => Category::where('is_active', true)->count(),
        ]);

        return view('subscription.plans', compact('plans', 'subscription', 'user', 'interestedPlans', 'stats'));
    }

    /**
     * Register interest in a not-yet-available plan (Basic/Premium).
     */
    public function showInterest(Request $request, string $plan): RedirectResponse
    {
        if (!array_key_exists($plan, Subscription::$plans) || Subscription::$plans[$plan]['available']) {
            abort(404);
        }

        $user = $request->user();

        $data = $request->validate([
            'email' => $user ? ['nullable'] : ['required', 'email', 'max:255'],
        ]);

        PlanInterest::firstOrCreate([
            'email' => $user->email ?? $data['email'],
            'plan'  => $plan,
        ], [
            'user_id' => $user?->id,
        ]);

        return back()->with('success', "Thanks! We'll email you as soon as the " . Subscription::$plans[$plan]['name'] . ' plan launches.');
    }

    /**
     * User selects the free plan — activate it and redirect.
     */
    public function selectFree(Request $request): RedirectResponse
    {
        $user = $request->user();

        Subscription::activateFree($user);

        // Selecting a plan is how a plain user signals they want to list a
        // business — promote them to owner so onboarding is reachable.
        if ($user->isUser()) {
            $ownerRole = Role::getBySlug('owner');
            if ($ownerRole) {
                $user->update(['role_id' => $ownerRole->id]);
            }
        }

        // Owners go to onboarding (or dashboard if they already have listings)
        if ($user->isOwner() || $user->isAdmin()) {
            if ($user->listings()->count() > 0) {
                return redirect()->route('owner.dashboard')
                    ->with('success', 'Welcome back! You are on the Free plan.');
            }
            // Show tour after onboarding completes (set flag, tour fires on first dashboard visit)
            session(['show_tour' => true]);
            return redirect()->route('owner.onboarding.start')
                ->with('success', 'You\'re on the Free plan! Let\'s list your business — a tour guide will help you get started.');
        }

        return redirect()->route('home')
            ->with('success', 'You\'re all set! Welcome to Hello Alibaug.');
    }

    /**
     * Dismiss / mark tour as seen.
     */
    public function dismissTour(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->session()->forget('show_tour');
        $request->user()?->update(['tour_seen' => true]);
        return response()->json(['ok' => true]);
    }
}
