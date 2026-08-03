<x-guest-layout>
    <div class="mb-8 p-1 opacity-0 translate-y-4 animate-[slideUpFade_0.6s_ease-out_forwards]">
        <h1 class="text-3xl font-serif font-bold text-slate-900 mb-2 tracking-tight">Welcome back</h1>
        <p class="text-slate-500 text-base font-medium">Sign in to your <span class="text-slate-900 font-bold">Hello Alibaug</span> account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-input-error :messages="$errors->get('social')" class="mb-4 text-sm text-red-500 font-medium" />

    <!-- Social Login Buttons -->
    <div class="space-y-3 mb-6 opacity-0 translate-y-4 animate-[slideUpFade_0.6s_ease-out_0.05s_forwards]">
        <a href="{{ route('social.redirect', 'google') }}"
           class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 font-bold text-sm hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Sign in with Google</span>
        </a>

        <a href="{{ route('social.redirect', 'facebook') }}"
           class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-[#1877F2] hover:bg-[#166FE5] text-white rounded-xl font-bold text-sm transition-all shadow-sm">
            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            <span>Sign in with Facebook</span>
        </a>
    </div>

    <div class="relative flex py-2 items-center mb-6 opacity-0 translate-y-4 animate-[slideUpFade_0.6s_ease-out_0.08s_forwards]">
        <div class="flex-grow border-t border-slate-200"></div>
        <span class="flex-shrink mx-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Or sign in with email</span>
        <div class="flex-grow border-t border-slate-200"></div>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6 opacity-0 translate-y-4 animate-[slideUpFade_0.6s_ease-out_0.1s_forwards]">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px] group-focus-within:text-amber-500 transition-colors">mail</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 text-base font-medium transition-all duration-300"
                    placeholder="you@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500 font-medium" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px] group-focus-within:text-amber-500 transition-colors">lock</span>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 text-base font-medium transition-all duration-300"
                    placeholder="Enter your password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500 font-medium" />
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-4 h-4 rounded appearance-none border-2 border-slate-300 checked:border-amber-500 checked:bg-amber-500 focus:ring-amber-500/30 focus:ring-offset-0 transition-colors">
                <span class="ms-2.5 text-sm font-medium text-slate-600 group-hover:text-slate-900 transition-colors">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-amber-600 font-bold hover:text-amber-700 transition-colors">Forgot password?</a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full relative group overflow-hidden rounded-xl font-bold text-base text-white py-3.5 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5"
                style="background: linear-gradient(135deg, #e8a020 0%, #f5c842 100%); box-shadow: 0 8px 25px -4px rgba(232,160,32,0.4);">
            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
            <span class="relative z-10">Sign In</span>
        </button>

        <div class="pt-6 border-t border-slate-100 text-center">
            <p class="text-base text-slate-500 font-medium">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-amber-600 font-bold hover:text-amber-700 hover:underline transition-all">Create an account</a>
            </p>
        </div>
    </form>
</x-guest-layout>
