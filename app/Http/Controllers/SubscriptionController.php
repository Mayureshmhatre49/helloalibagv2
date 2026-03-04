<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('subscription.plans', compact('plans', 'subscription', 'user'));
    }

    /**
     * User selects the free plan — activate it and redirect.
     */
    public function selectFree(Request $request): RedirectResponse
    {
        $user = $request->user();

        Subscription::activateFree($user);

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
