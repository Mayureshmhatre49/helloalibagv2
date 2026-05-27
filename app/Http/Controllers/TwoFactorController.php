<?php

namespace App\Http\Controllers;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function setup(Request $request)
    {
        $user = $request->user();

        if (!$user->two_factor_secret) {
            $google2fa = app('pragmarx.google2fa');
            $secret = $google2fa->generateSecretKey();
            $user->update(['two_factor_secret' => $secret]);
        }

        $google2fa = app('pragmarx.google2fa');
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $qrSvg = (new Writer($renderer))->writeString($qrCodeUrl);

        return view('auth.2fa.setup', [
            'secret' => $user->two_factor_secret,
            'qrSvg' => $qrSvg,
        ]);
    }

    public function confirmSetup(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string|size:6',
        ]);

        $user = $request->user();
        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->input('one_time_password'));

        if (!$valid) {
            return back()->withErrors(['one_time_password' => 'Invalid code. Please try again.']);
        }

        $user->update(['two_factor_confirmed_at' => now()]);
        session(['2fa.verified' => true]);

        return redirect()->intended(route('admin.dashboard'))->with('success', '2FA enabled successfully.');
    }

    public function challenge()
    {
        return view('auth.2fa.challenge');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string|size:6',
        ]);

        $user = $request->user();
        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->input('one_time_password'));

        if (!$valid) {
            return back()->withErrors(['one_time_password' => 'Invalid code. Please try again.']);
        }

        session(['2fa.verified' => true]);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function disable(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string|size:6',
        ]);

        $user = $request->user();
        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->input('one_time_password'));

        if (!$valid) {
            return back()->withErrors(['one_time_password' => 'Invalid code. Please try again.']);
        }

        $user->update([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ]);
        session()->forget('2fa.verified');

        return redirect()->back()->with('success', '2FA has been disabled.');
    }
}
