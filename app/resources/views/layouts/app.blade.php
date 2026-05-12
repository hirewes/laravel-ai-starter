<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Laravel AI Starter')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-slate-950 text-slate-100 antialiased" x-data="appShell()" x-init="init()">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.18),_transparent_40%),linear-gradient(180deg,_#020617_0%,_#0f172a_100%)]">
            @include('layouts.partials.navigation')

            <main class="mx-auto w-full max-w-7xl px-4 pb-10 pt-6 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>

        <div
            x-cloak
            x-show="toast.open"
            x-transition.opacity
            class="fixed bottom-4 right-4 z-50 w-full max-w-sm px-4"
        >
            <div class="rounded-2xl border border-white/10 bg-slate-900/95 p-4 shadow-2xl shadow-slate-950/50 backdrop-blur">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 h-2.5 w-2.5 rounded-full" :class="toast.type === 'error' ? 'bg-rose-400' : 'bg-emerald-400'"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white" x-text="toast.message"></p>
                    </div>
                    <button type="button" class="text-slate-400 transition hover:text-white" @click="toast.open = false">
                        <span class="sr-only">Close</span>
                        &times;
                    </button>
                </div>
            </div>
        </div>

        @if (session('toast'))
            <script>
                window.__FLASH_TOAST__ = @json(session('toast'));
            </script>
        @endif
    </body>
</html>
