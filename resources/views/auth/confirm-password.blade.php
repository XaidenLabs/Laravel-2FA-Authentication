<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm Password - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-6">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Access Restricted</h1>
            <p class="mt-3 text-slate-600">For your security, please confirm your password to continue.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 p-8 border border-slate-100">
            <form action="{{ url('/user/confirm-password') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all p-3"
                            required
                            autocomplete="current-password"
                            autofocus
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all uppercase tracking-wider"
                    >
                        Confirm Password
                    </button>
                </div>
            </form>
            
            <div class="mt-8 pt-8 border-t border-slate-100 text-center">
                <a href="{{ route('home') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
