<x-guest-layout>
    <div class="mb-8 p-1 opacity-0 translate-y-4 animate-[slideUpFade_0.6s_ease-out_forwards]">
        <h1 class="text-3xl font-serif font-bold text-slate-900 mb-2 tracking-tight">Create your account</h1>
        <p class="text-slate-500 text-base font-medium">Join <span class="text-slate-900 font-bold">Hello Alibaug</span> and start listing or exploring</p>
    </div>

    <!-- Social Register Buttons -->
    <div class="space-y-3 mb-6 opacity-0 translate-y-4 animate-[slideUpFade_0.6s_ease-out_0.05s_forwards]">
        <a href="{{ route('social.redirect', 'google') }}"
           class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 font-bold text-sm hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Continue with Google</span>
        </a>

        {{-- Facebook login disabled for now — re-add when FACEBOOK_CLIENT_ID/SECRET are configured. --}}
    </div>

    <div class="relative flex py-2 items-center mb-6 opacity-0 translate-y-4 animate-[slideUpFade_0.6s_ease-out_0.08s_forwards]">
        <div class="flex-grow border-t border-slate-200"></div>
        <span class="flex-shrink mx-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Or register with email</span>
        <div class="flex-grow border-t border-slate-200"></div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6 opacity-0 translate-y-4 animate-[slideUpFade_0.6s_ease-out_0.1s_forwards]">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px] group-focus-within:text-amber-500 transition-colors">person</span>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 text-base font-medium transition-all duration-300"
                    placeholder="John Doe">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-500 font-medium" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px] group-focus-within:text-amber-500 transition-colors">mail</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 text-base font-medium transition-all duration-300"
                    placeholder="you@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500 font-medium" />
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">Phone Number <span class="text-slate-500 font-normal">(Optional)</span></label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px] group-focus-within:text-amber-500 transition-colors">call</span>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 text-base font-medium transition-all duration-300"
                    placeholder="xxxxx xxxxx"
                    onblur="this.value.trim() === '' || this.value.match(/^(\+91[\s-]?)?[0-9]{10}$/) ? this.setCustomValidity('') : this.setCustomValidity('Please enter a valid 10-digit mobile number'); this.reportValidity();"
                    oninput="this.setCustomValidity('')">
            </div>
            <p class="mt-1 text-xs text-slate-500 hidden" id="phone-feedback"></p>
        </div>

        <!-- Account Type -->
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">I want to</label>
            <div class="grid grid-cols-2 gap-4">
                <label class="flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 hover:border-amber-500/50 border-slate-200 group">
                    <input type="radio" name="account_type" value="user" class="w-5 h-5 text-amber-500 focus:ring-amber-500/30 border-slate-300" checked>
                    <div>
                        <span class="text-sm font-bold text-slate-900 group-has-[:checked]:text-amber-700">Explore</span>
                        <p class="text-xs text-slate-500 font-medium">Browse & book</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 hover:border-amber-500/50 border-slate-200 group">
                    <input type="radio" name="account_type" value="owner" class="w-5 h-5 text-amber-500 focus:ring-amber-500/30 border-slate-300">
                    <div>
                        <span class="text-sm font-bold text-slate-900 group-has-[:checked]:text-amber-700">List</span>
                        <p class="text-xs text-slate-500 font-medium">Property owner</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px] group-focus-within:text-amber-500 transition-colors">lock</span>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 text-base font-medium transition-all duration-300"
                    placeholder="Min. 8 characters">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500 font-medium" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Confirm Password</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px] group-focus-within:text-amber-500 transition-colors">lock</span>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 text-base font-medium transition-all duration-300"
                    placeholder="Re-enter password">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-500 font-medium" />
        </div>

        <x-turnstile />

        <!-- Submit -->
        <button type="submit" class="mt-6 w-full relative group overflow-hidden rounded-xl font-bold text-base text-white py-3.5 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5"
                style="background: linear-gradient(135deg, #e8a020 0%, #f5c842 100%); box-shadow: 0 8px 25px -4px rgba(232,160,32,0.4);">
            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
            <span class="relative z-10">Create Account</span>
        </button>

        <div class="pt-6 border-t border-slate-100 text-center">
            <p class="text-base text-slate-500 font-medium">
                Already have an account?
                <a href="{{ route('login') }}" class="text-amber-600 font-bold hover:text-amber-700 hover:underline transition-all">Sign in</a>
            </p>
        </div>
    </form>
</x-guest-layout>
