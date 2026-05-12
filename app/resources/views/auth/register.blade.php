@extends('layouts.guest')

@section('title', 'Register | Laravel AI Starter')

@section('content')
    <div class="grid w-full max-w-6xl gap-10 lg:grid-cols-[0.95fr_1.05fr]">
        <div class="rounded-[2rem] border border-white/10 bg-slate-900/85 p-8 shadow-2xl shadow-slate-950/50 backdrop-blur">
            <div class="mb-8">
                <p class="text-sm uppercase tracking-[0.25em] text-slate-400">Create account</p>
                <h2 class="mt-3 text-3xl font-semibold text-white">Start building with Laravel AI Starter</h2>
                <p class="mt-3 text-sm text-slate-400">Get a production-ready workspace with authentication, queued AI responses, dashboards, and a premium UX.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-200">Full name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="form-input" placeholder="Ava Founder">
                    @error('name') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-200">Email address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="form-input" placeholder="ava@example.com">
                    @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-200">Password</label>
                        <input id="password" name="password" type="password" required class="form-input" placeholder="Minimum 8 characters">
                        @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-200">Confirm password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="form-input" placeholder="Repeat password">
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full justify-center">Create workspace</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-400">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-white transition hover:text-blue-200">Sign in</a>
            </p>
        </div>

        <div class="hidden rounded-[2rem] border border-white/10 bg-white/5 p-10 shadow-2xl shadow-slate-950/50 backdrop-blur lg:block">
            <div class="grid gap-5">
                <div class="rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-6">
                    <p class="text-xs uppercase tracking-[0.25em] text-emerald-100/80">Included</p>
                    <p class="mt-3 text-xl font-semibold text-white">AI chat, REST API, queues, rate limiting, dark mode, and clean Laravel architecture.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-5">
                        <p class="text-sm font-medium text-white">Conversation history</p>
                        <p class="mt-2 text-sm text-slate-400">Track prompts, responses, and token usage per chat thread.</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-5">
                        <p class="text-sm font-medium text-white">Streaming-like UX</p>
                        <p class="mt-2 text-sm text-slate-400">Poll async jobs and reveal AI replies with a typing animation.</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-5">
                        <p class="text-sm font-medium text-white">Thin controllers</p>
                        <p class="mt-2 text-sm text-slate-400">Services and repositories keep business logic easy to extend.</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-5">
                        <p class="text-sm font-medium text-white">Responsive by default</p>
                        <p class="mt-2 text-sm text-slate-400">Mobile-friendly layouts designed to feel like premium SaaS products.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
