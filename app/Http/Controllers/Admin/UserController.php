<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')->withCount(['listings', 'reviews', 'classifieds', 'bookings']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('role', function($q) use ($request) {
                $q->where('slug', $request->role);
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Suspend or reactivate a user. Suspending also kills their active
     * sessions so it takes effect immediately, not just on their next login.
     */
    public function toggleStatus(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot suspend an administrator.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        if (! $user->is_active) {
            \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $user->id)->delete();

            return back()->with('success', "{$user->name} has been suspended and signed out.");
        }

        return back()->with('success', "{$user->name} has been reactivated.");
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete an administrator.');
        }

        $this->deleteUserAndFiles($user);

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Delete a user along with their listings' image files.
     *
     * The database cascades listings away with the user, but a DB-level
     * cascade does not fire Eloquent model events — so ListingObserver never
     * runs and the image files are orphaned on disk. Deleting the listings
     * through Eloquent first lets the observer clean the files up.
     */
    private function deleteUserAndFiles(User $user): void
    {
        foreach ($user->listings()->with('images')->get() as $listing) {
            $listing->delete();
        }

        $user->delete();
    }

    /**
     * Delete several users at once. Administrators and the current user are
     * never deleted, even if their id is submitted.
     */
    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer', 'exists:users,id'],
        ]);

        $deleted = User::query()
            ->whereIn('id', $data['ids'])
            ->where('id', '!=', auth()->id())
            ->whereDoesntHave('role', fn ($q) => $q->where('slug', 'admin'))
            ->get();

        $count = $deleted->count();
        $skipped = count($data['ids']) - $count;

        foreach ($deleted as $user) {
            $this->deleteUserAndFiles($user);
        }

        if ($count === 0) {
            return back()->with('error', 'No users were deleted (administrators and your own account are protected).');
        }

        $msg = "{$count} user" . ($count === 1 ? '' : 's') . ' deleted.';
        if ($skipped > 0) {
            $msg .= " {$skipped} skipped (administrators or your own account).";
        }

        return back()->with('success', $msg);
    }

    /**
     * Turn two-factor authentication on or off for a user.
     * Disabling also clears any existing secret so re-enabling starts fresh.
     */
    public function toggleTwoFactor(User $user)
    {
        if ($user->two_factor_enabled) {
            $user->update([
                'two_factor_enabled' => false,
                'two_factor_secret' => null,
                'two_factor_confirmed_at' => null,
            ]);

            return back()->with('success', "Two-factor authentication disabled for {$user->name}.");
        }

        $user->update(['two_factor_enabled' => true]);

        return back()->with('success', "Two-factor authentication enabled for {$user->name}. They'll set it up at their next admin login.");
    }

    /**
     * Set a new password for a user.
     */
    public function setPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->forceFill(['password' => Hash::make($request->input('password'))])->save();

        return back()->with('success', "Password updated for {$user->name}.");
    }
}
