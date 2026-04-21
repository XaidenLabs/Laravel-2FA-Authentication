<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Validation\ValidationException;

class OtpChallengeController extends Controller
{
    public function __construct(protected OtpService $otpService)
    {

    }

    public function show(Request $request)
    {
        if (! $request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        return view('auth.otp-challenge');
    }

    public function send(Request $request)
    {
        $request->validate([
            'method' => ['required', 'string', 'in:email,sms'],
        ]);

        if (! $request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($request->session()->get('login.id'));

        $originalPreference = $user->otp_preference;
        $user->otp_preference = $request->input('method');

        $code = $this->otpService->generate($user);
        $user->notify(new \App\Notifications\SendOtpCode($code));

        $user->otp_preference = $originalPreference;

        return back()->with('status', 'code-sent')->with('method', $request->input('method'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        if (! $request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($request->session()->get('login.id'));

        if (! $this->otpService->verify($user, $request->code)) {
            throw ValidationException::withMessages([
                'code' => ['The provided code was invalid or has expired.'],
            ]);
        }

        auth()->guard()->login($user, $request->session()->get('login.remember'));

        $request->session()->forget(['login.id', 'login.remember']);

        return redirect()->intended(config('fortify.home'));
    }
}
