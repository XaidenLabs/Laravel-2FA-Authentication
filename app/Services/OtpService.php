<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class OtpService
{
    
    public function generate(User $user): string
    {
        $code = (string) random_int(100000, 999999);
        
        Cache::put($this->getCacheKey($user), $code, now()->addMinutes(5));

        return $code;
    }

    public function verify(User $user, string $code): bool
    {

        $cachedCode = Cache::get($this->getCacheKey($user));

        if ($cachedCode && hash_equals($cachedCode, (string) $code)) {
            Cache::forget($this->getCacheKey($user));
            return true;
        }

        if ($user->two_factor_secret) {
            return app(\Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider::class)
                ->verify(decrypt($user->two_factor_secret), (string) $code);
        }

        return false;
    }

    protected function getCacheKey(User $user): string
    {
        return 'otp_' . $user->id;
    }
}
