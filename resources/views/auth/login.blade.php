<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-serif font-bold text-slate-900 mb-2">Welcome back</h1>
        <p class="text-slate-500 text-sm">Sign in to your Hello Alibaug account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-1.5">Email Address</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">mail</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm font-medium"
                    placeholder="you@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">Password</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">lock</span>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm font-medium"
                    placeholder="Enter your password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-slate-300 text-primary shadow-sm focus:ring-primary">
                <span class="ms-2 text-sm text-slate-600">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-primary font-medium hover:underline">Forgot password?</a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20">
            Sign In
        </button>

        <p class="text-center text-sm text-slate-500 mt-4">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">Create one</a>
        </p>
    </form>
</x-guest-layout>
