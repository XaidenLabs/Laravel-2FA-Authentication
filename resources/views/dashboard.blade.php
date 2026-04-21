<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <nav class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-xl font-bold text-indigo-600 tracking-tight">{{ config('app.name') }}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-slate-700">{{ Auth::user()->name }}</span>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors">Sign Out</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-slate-900">Security Settings</h3>
                    <p class="mt-1 text-sm text-slate-600">
                        Manage your account's multi-factor authentication settings to keep your account secure.
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 sm:rounded-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h4 class="text-base font-semibold text-slate-900">Multi-Factor Authentication</h4>
                                <p class="text-sm text-slate-500">
                                    @if (Auth::user()->two_factor_confirmed_at)
                                        <span class="text-green-600 font-medium">Currently Enabled</span>
                                    @else
                                        <span class="text-slate-500">Not currently enabled.</span>
                                    @endif
                                </p>
                            </div>
                            
                            @if (! Auth::user()->two_factor_secret)
                                <form method="POST" action="/user/two-factor-authentication">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition-all">
                                        Enable MFA
                                    </button>
                                </form>
                            @else
                                <div class="flex space-x-3">
                                    @if (! Auth::user()->two_factor_confirmed_at)
                                        <div class="text-right">
                                            <p class="text-xs text-amber-600 font-medium mb-1">Unconfirmed</p>
                                        </div>
                                    @endif
                                    <form method="POST" action="/user/two-factor-authentication">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition-all">
                                            Disable MFA
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        @if (Auth::user()->two_factor_secret && ! Auth::user()->two_factor_confirmed_at)
                            <div class="mt-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                                <h5 class="text-sm font-bold text-slate-900 mb-4">Complete Setup</h5>
                                <div class="flex flex-col md:flex-row md:items-start md:space-x-8">
                                    <div class="bg-white p-2 rounded-lg border border-slate-200 inline-block mb-4 md:mb-0">
                                        {!! Auth::user()->twoFactorQrCodeSvg() !!}
                                    </div>
                                    <div class="flex-1 space-y-4">
                                        <p class="text-sm text-slate-600">
                                            Scan the QR code above with your authenticator app, then enter the verification code below to confirm setup.
                                        </p>
                                        <form method="POST" action="/user/confirmed-two-factor-authentication">
                                            @csrf
                                            <div class="flex space-x-3">
                                                <input type="text" name="code" placeholder="6-digit code" class="block w-40 rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-900 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 transition-all">
                                                    Confirm
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (Auth::user()->two_factor_confirmed_at)
                            <div class="mt-6 border-t border-slate-100 pt-6">
                                <h5 class="text-sm font-bold text-slate-900 mb-4">MFA Configuration</h5>
                                
                                <form method="POST" action="{{ route('user.mfa.preference') }}" class="space-y-6">
                                    @csrf
                                    <div>
                                        <label for="phone_number" class="block text-sm font-medium text-slate-700 mb-2">Phone Number (for SMS OTP)</label>
                                        <div class="flex space-x-3">
                                            <input type="text" name="phone_number" value="{{ Auth::user()->phone_number }}" placeholder="+1234567890" class="block w-full max-w-xs rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-900 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 transition-all">
                                                Update
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-slate-700 mb-2">Preferred Delivery Method</p>
                                        <div class="flex items-center space-x-6">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="otp_preference" value="totp" {{ Auth::user()->otp_preference === 'totp' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500" onchange="this.form.submit()">
                                                <span class="ml-2 text-sm text-slate-600">Authenticator App</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="otp_preference" value="email" {{ Auth::user()->otp_preference === 'email' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500" onchange="this.form.submit()">
                                                <span class="ml-2 text-sm text-slate-600">Email OTP</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="otp_preference" value="sms" {{ Auth::user()->otp_preference === 'sms' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500" onchange="this.form.submit()">
                                                <span class="ml-2 text-sm text-slate-600">SMS OTP</span>
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
