<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('dashboard');
})->middleware(['auth'])->name('home');

Route::post('/user/mfa-preference', function (Illuminate\Http\Request $request) {
    $request->validate([
        'otp_preference' => ['sometimes', 'required', 'string', 'in:totp,email,sms'],
        'phone_number' => ['sometimes', 'nullable', 'string', 'max:20'],
    ]);

    $request->user()->update(
        $request->only(['otp_preference', 'phone_number'])
    );

    return back();
})->middleware(['auth'])->name('user.mfa.preference');

Route::prefix('auth')->group(function () {
    Route::get('/otp-challenge', [App\Http\Controllers\Auth\OtpChallengeController::class, 'show'])
        ->name('otp.challenge');

    Route::post('/otp-challenge', [App\Http\Controllers\Auth\OtpChallengeController::class, 'store'])
        ->middleware(['throttle:two-factor']);

    Route::post('/otp-send', [App\Http\Controllers\Auth\OtpChallengeController::class, 'send'])
        ->name('otp.send')
        ->middleware(['throttle:two-factor']);
});
