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
        <div class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.18),_transparent_35%),linear-gradient(180deg,_#020617_0%,_#0f172a_100%)]">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:72px_72px] opacity-20"></div>

            <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 text-sm font-semibold tracking-[0.2em] text-slate-200 uppercase">
                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-400 text-base font-bold text-slate-950 shadow-lg shadow-blue-500/20">AI</span>
                        Laravel AI Starter
                    </a>

                    <button
                        type="button"
                        class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-200 transition hover:bg-white/10"
                        @click="toggleDarkMode()"
                    >
                        Toggle theme
                    </button>
                </div>

                <div class="flex flex-1 items-center justify-center py-12">
                    @yield('content')
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
