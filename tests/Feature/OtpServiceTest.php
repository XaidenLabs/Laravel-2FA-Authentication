<?php

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->otpService = new OtpService();
    $this->user = User::factory()->create();
});

test('it can generate an otp', function () {
    $code = $this->otpService->generate($this->user);

    expect($code)->toHaveLength(6)
        ->and(is_numeric($code))->toBeTrue();
    
    expect(Cache::get('otp_' . $this->user->id))->toBe($code);
});

test('it can verify a correct otp', function () {
    $code = $this->otpService->generate($this->user);

    expect($this->otpService->verify($this->user, $code))->toBeTrue();
    expect(Cache::get('otp_' . $this->user->id))->toBeNull();
});

test('it fails to verify an incorrect otp', function () {
    $this->otpService->generate($this->user);

    expect($this->otpService->verify($this->user, '000000'))->toBeFalse();
});

test('it fails to verify an expired otp', function () {
    $code = $this->otpService->generate($this->user);

    Cache::forget('otp_' . $this->user->id);

    expect($this->otpService->verify($this->user, $code))->toBeFalse();
});
