<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold text-slate-900 mb-2 tracking-tight">Two-Factor Authentication</h1>
        <p class="text-slate-500 text-base font-medium">Enter the 6-digit code from your authenticator app to continue.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-6">
        @csrf

        <div>
            <label for="one_time_password" class="block text-sm font-bold text-slate-700 mb-2">Authentication Code</label>
            <input id="one_time_password" type="text" name="one_time_password" inputmode="numeric" pattern="[0-9]*" maxlength="6"
                required autofocus autocomplete="one-time-code"
                class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-center text-2xl tracking-[0.5em] font-mono focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all duration-300"
                placeholder="000000">
            <x-input-error :messages="$errors->get('one_time_password')" class="mt-2 text-sm text-red-500 font-medium" />
        </div>

        <button type="submit"
            class="w-full py-3.5 px-6 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-colors duration-200 text-base">
            Verify
        </button>
    </form>
</x-guest-layout>
