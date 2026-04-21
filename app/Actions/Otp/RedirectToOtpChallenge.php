<?php

namespace App\Actions\Otp;

use App\Models\User;
use App\Notifications\SendOtpCode;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;

class RedirectToOtpChallenge
{
    public function __construct(protected OtpService $otpService)
    {

    }

    public function __invoke(Request $request, $next)
    {
        $user = $request->user();

        if ($user && $user->two_factor_secret && $user->two_factor_confirmed_at) {
            auth()->guard()->logout();

            $request->session()->put('login.id', $user->getAuthIdentifier());
            $request->session()->put('login.remember', $request->boolean('remember'));

            return redirect()->route('otp.challenge');
        }

        return $next($request);
    }
}
