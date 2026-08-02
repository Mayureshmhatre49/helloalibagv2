{{--
    Cloudflare Turnstile widget. Renders nothing at all until
    TURNSTILE_SITE_KEY is set — every form using this stays fully functional,
    with no CAPTCHA required, until the account is actually configured.

    Submits its token as `cf-turnstile-response`, matched by app\Rules\Captcha
    on the 'cf-turnstile-response' field in the controller's validation rules.
--}}
@if(config('services.turnstile.site_key'))
    @once
        @push('scripts')
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endpush
    @endonce

    <div class="cf-turnstile my-2"
         data-sitekey="{{ config('services.turnstile.site_key') }}"
         data-theme="light"
         {{-- 'auto' only shows an interactive challenge when Cloudflare's risk
              score calls for one — most real visitors see nothing at all. --}}
         data-appearance="interaction-only">
    </div>
    @error('cf-turnstile-response')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
@endif
