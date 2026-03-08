<x-guest-layout>
    <div class="mb-8 p-1 opacity-0 translate-y-4 animate-[slideUpFade_0.6s_ease-out_forwards]">
        <h1 class="text-3xl font-serif font-bold text-slate-900 mb-2 tracking-tight">Welcome back</h1>
        <p class="text-slate-500 text-base font-medium">Sign in to your <span class="text-slate-900 font-bold">Hello Alibaug</span> account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

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
