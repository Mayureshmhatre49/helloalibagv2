<x-guest-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold text-slate-900 mb-2 tracking-tight">Set Up Two-Factor Authentication</h1>
        <p class="text-slate-500 text-base font-medium">Scan the QR code with your authenticator app (e.g. Google Authenticator, Authy), then enter the 6-digit code to confirm.</p>
    </div>

    <div class="flex flex-col items-center mb-6">
        <div class="w-48 h-48 mb-4 border rounded-xl p-2 bg-white flex items-center justify-center">{!! $qrSvg !!}</div>
        <p class="text-sm text-slate-500">Or enter this secret manually:</p>
        <code class="mt-1 px-3 py-1.5 bg-slate-100 rounded-lg text-slate-800 font-mono tracking-widest text-sm select-all">{{ $secret }}</code>
    </div>

    <form method="POST" action="{{ route('2fa.setup.confirm') }}" class="space-y-6">
        @csrf

        <div>
            <label for="one_time_password" class="block text-sm font-bold text-slate-700 mb-2">Verification Code</label>
            <input id="one_time_password" type="text" name="one_time_password" inputmode="numeric" pattern="[0-9]*" maxlength="6"
                required autofocus autocomplete="one-time-code"
                class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-center text-2xl tracking-[0.5em] font-mono focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all duration-300"
                placeholder="000000">
            <x-input-error :messages="$errors->get('one_time_password')" class="mt-2 text-sm text-red-500 font-medium" />
        </div>

        <button type="submit"
            class="w-full py-3.5 px-6 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-colors duration-200 text-base">
            Confirm & Enable 2FA
        </button>
    </form>
</x-guest-layout>
