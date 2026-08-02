<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Verifies a Cloudflare Turnstile token server-side.
 *
 * A no-op — the form submits normally with no CAPTCHA required — until
 * TURNSTILE_SECRET_KEY is actually set, so deploying this doesn't break any
 * form before the account is set up. If Cloudflare's verification endpoint
 * itself is unreachable, this fails OPEN (allows the submission) rather than
 * blocking a real customer over a third-party outage — spam is an annoyance,
 * a lost lead during an outage is a lost booking.
 */
class Captcha implements ValidationRule
{
    private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $secret = config('services.turnstile.secret_key');

        if (empty($secret)) {
            return;
        }

        if (empty($value)) {
            $fail('Please complete the verification check.');
            return;
        }

        try {
            $response = Http::asForm()->timeout(8)->post(self::VERIFY_URL, [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            if (! $response->successful()) {
                Log::warning('Turnstile verify endpoint returned HTTP ' . $response->status());
                return; // fail open — Cloudflare-side issue, not the visitor's fault
            }

            if (! ($response->json('success') ?? false)) {
                $fail('Verification failed — please try again.');
            }
        } catch (\Throwable $e) {
            Log::warning('Turnstile verification error: ' . $e->getMessage());
            // fail open
        }
    }
}
