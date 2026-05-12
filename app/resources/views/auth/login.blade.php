@extends('layouts.guest')

@section('title', 'Login | Laravel AI Starter')

@section('content')
    <div class="grid w-full max-w-6xl gap-10 lg:grid-cols-[1.1fr_0.9fr]">
        <div class="hidden rounded-[2rem] border border-white/10 bg-white/5 p-10 shadow-2xl shadow-slate-950/50 backdrop-blur xl:block">
            <span class="inline-flex rounded-full border border-blue-400/30 bg-blue-400/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-blue-200">
                Premium AI SaaS Starter
            </span>
            <h1 class="mt-6 text-4xl font-semibold leading-tight text-white">Launch an AI-ready Laravel product with clean architecture and a premium experience.</h1>
            <p class="mt-4 max-w-xl text-base leading-7 text-slate-300">
                Authentication, queue-based OpenAI workflows, conversation history, dashboards, REST APIs, and a modern Tailwind interface are already part of the foundation.
            </p>
            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-5">
                    <p class="text-sm font-medium text-white">Queue-backed AI</p>
                    <p class="mt-2 text-sm text-slate-400">Dispatch prompts to jobs and keep the UI fast with async responses.</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-5">
                    <p class="text-sm font-medium text-white">SaaS-grade UI</p>
                    <p class="mt-2 text-sm text-slate-400">Responsive layouts, dark mode, loading states, and elegant empty states.</p>
                </div>
            </div>
        </div>

        <div class="rounded-[2rem] border border-white/10 bg-slate-900/85 p-8 shadow-2xl shadow-slate-950/50 backdrop-blur">
            <div class="mb-8">
                <p class="text-sm uppercase tracking-[0.25em] text-slate-400">Welcome back</p>
                <h2 class="mt-3 text-3xl font-semibold text-white">Sign in to your workspace</h2>
                <p class="mt-3 text-sm text-slate-400">Manage conversations, review token usage, and continue building with AI.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-200">Email address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="form-input" placeholder="founder@example.com">
                    @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-slate-200">Password</label>
                    </div>
                    <input id="password" name="password" type="password" required class="form-input" placeholder="Enter your password">
                    @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300">
                    <input type="checkbox" name="remember" class="rounded border-white/20 bg-slate-950 text-blue-500 focus:ring-blue-500">
                    Keep me signed in
                </label>

                <button type="submit" class="btn-primary w-full justify-center">Sign in</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-400">
                Need an account?
                <a href="{{ route('register') }}" class="font-medium text-white transition hover:text-blue-200">Create one now</a>
            </p>
        </div>
    </div>
@endsection
