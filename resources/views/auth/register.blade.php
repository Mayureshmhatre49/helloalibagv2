<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-serif font-bold text-slate-900 mb-2">Create your account</h1>
        <p class="text-slate-500 text-sm">Join Hello Alibaug and start listing or exploring</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Full Name</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">person</span>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm font-medium"
                    placeholder="John Doe">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-1.5">Email Address</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">mail</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm font-medium"
                    placeholder="you@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-sm font-bold text-slate-700 mb-1.5">Phone Number <span class="text-slate-400 font-normal">(Optional)</span></label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">call</span>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm font-medium"
                    placeholder="+91 98765 43210">
            </div>
        </div>

        <!-- Account Type -->
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">I want to</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5 border-slate-200 hover:border-slate-300">
                    <input type="radio" name="account_type" value="user" class="text-primary focus:ring-primary" checked>
                    <div>
                        <span class="text-sm font-bold text-slate-900">Explore</span>
                        <p class="text-xs text-slate-500">Browse & book</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5 border-slate-200 hover:border-slate-300">
                    <input type="radio" name="account_type" value="owner" class="text-primary focus:ring-primary">
                    <div>
                        <span class="text-sm font-bold text-slate-900">List</span>
                        <p class="text-xs text-slate-500">Property owner</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">Password</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">lock</span>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm font-medium"
                    placeholder="Min. 8 characters">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-1.5">Confirm Password</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">lock</span>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm font-medium"
                    placeholder="Re-enter password">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20">
            Create Account
        </button>

        <p class="text-center text-sm text-slate-500">
            Already have an account?
            <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Sign in</a>
        </p>
    </form>
</x-guest-layout>
