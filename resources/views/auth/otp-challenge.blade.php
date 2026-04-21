<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Two-Factor Challenge - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-6">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Security Verification</h1>
            <p class="mt-3 text-slate-600">
                @if (session('status') === 'code-sent')
                    <span class="text-indigo-600 font-semibold">Code sent via {{ strtoupper(session('method')) }}.</span>
                    Please enter the 6-digit code received.
                @else
                    Enter the code from your <strong>Authenticator App</strong>.
                @endif
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 p-8 border border-slate-100">
            <form action="{{ request()->routeIs('otp.challenge') ? route('otp.challenge') : url('/two-factor-challenge') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="code" class="block text-sm font-medium text-slate-700 mb-2">Verification Code</label>
                        <input 
                            type="text" 
                            name="code" 
                            id="code" 
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            pattern="[0-9]*"
                            maxlength="6"
                            placeholder="······"
                            class="block w-full text-center text-2xl tracking-[1em] py-4 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all placeholder:text-slate-300"
                            autofocus
                            required
                        >
                        @error('code')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all uppercase tracking-wider"
                    >
                        Verify & Login
                    </button>
                </div>
            </form>
            
            <div class="mt-8 pt-8 border-t border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 text-center">Or verify another way</p>
                <div class="grid grid-cols-2 gap-3">
                    <form action="{{ route('otp.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="method" value="email">
                        <button type="submit" class="w-full py-3 px-4 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Email
                        </button>
                    </form>
                    <form action="{{ route('otp.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="method" value="sms">
                        <button type="submit" class="w-full py-3 px-4 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            SMS
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Login
            </a>
        </div>
    </div>
</body>
</html>
