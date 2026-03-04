<x-guest-layout>
    <div class="mb-8 p-1 opacity-0 translate-y-4 animate-[slideUpFade_0.6s_ease-out_forwards]">
        <h1 class="text-3xl font-serif font-bold text-slate-900 mb-2 tracking-tight">Create your account</h1>
        <p class="text-slate-500 text-base font-medium">Join <span class="text-slate-900 font-bold">Hello Alibaug</span> and start listing or exploring</p>
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
            <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">Phone Number <span class="text-slate-400 font-normal">(Optional)</span></label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px] group-focus-within:text-amber-500 transition-colors">call</span>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 text-base font-medium transition-all duration-300"
                    placeholder="+91 98765 43210">
            </div>
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
