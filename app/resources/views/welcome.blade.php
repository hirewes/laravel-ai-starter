@extends('layouts.guest')

@section('title', 'Laravel AI Starter')

@section('content')
    <div class="w-full">
        <div class="mx-auto max-w-6xl">
            <div class="grid items-center gap-12 py-10 lg:grid-cols-[1.05fr_0.95fr]">
                <div>
                    <span class="inline-flex rounded-full border border-blue-400/30 bg-blue-400/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-blue-200">
                        Laravel 12 SaaS Starter
                    </span>
                    <h1 class="mt-6 max-w-3xl text-5xl font-semibold leading-tight text-white sm:text-6xl">
                        Build premium AI products with Laravel, queues, and a polished SaaS UI.
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                        Laravel AI Starter gives you authentication, a ChatGPT-style interface, conversation history, token tracking, REST APIs, and clean service-driven architecture inside a single modern starter.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-primary">Open dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="btn-primary">Start building</a>
                            <a href="{{ route('login') }}" class="btn-secondary">Sign in</a>
                        @endauth
                    </div>

                    <div class="mt-10 grid gap-4 sm:grid-cols-3">
                        <div class="card-glass p-5">
                            <p class="text-3xl font-semibold text-white">Queue-first</p>
                            <p class="mt-2 text-sm text-slate-400">Async AI requests with responsive UX and robust error handling.</p>
                        </div>
                        <div class="card-glass p-5">
                            <p class="text-3xl font-semibold text-white">REST-ready</p>
                            <p class="mt-2 text-sm text-slate-400">Versioned API resources and rate-limited chat endpoints.</p>
                        </div>
                        <div class="card-glass p-5">
                            <p class="text-3xl font-semibold text-white">SaaS polish</p>
                            <p class="mt-2 text-sm text-slate-400">Dark mode, dashboards, toasts, loading states, and mobile layouts.</p>
                        </div>
                    </div>
                </div>

                <div class="card-glass overflow-hidden p-6">
                    <div class="rounded-[1.75rem] border border-white/10 bg-slate-950/90 p-5">
                        <div class="flex items-center justify-between border-b border-white/10 pb-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">AI workspace</p>
                                <p class="mt-2 text-lg font-semibold text-white">Product strategy assistant</p>
                            </div>
                            <span class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-200">Queue active</span>
                        </div>

                        <div class="space-y-4 py-5">
                            <div class="chat-bubble-user">
                                Draft a launch plan for a B2B AI SaaS using Laravel, queues, and a clean API layer.
                            </div>
                            <div class="chat-bubble-assistant prose prose-invert max-w-none">
                                <p>Here is a pragmatic launch sequence:</p>
                                <ul>
                                    <li>Ship authentication, billing-ready account settings, and a responsive dashboard first.</li>
                                    <li>Move OpenAI requests to queues so the UI stays fast and recoverable under load.</li>
                                    <li>Expose the same chat workflow through versioned REST endpoints for future clients.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="grid gap-3 border-t border-white/10 pt-4 sm:grid-cols-3">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Conversations</p>
                                <p class="mt-3 text-2xl font-semibold text-white">128</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Tokens</p>
                                <p class="mt-3 text-2xl font-semibold text-white">93.2k</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Latency</p>
                                <p class="mt-3 text-2xl font-semibold text-white">1.9s</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                <div class="card-glass p-6">
                    <p class="text-sm font-semibold text-white">Authentication & settings</p>
                    <p class="mt-3 text-sm leading-7 text-slate-400">Laravel Breeze-powered auth flows with a unified settings screen for account and AI preferences.</p>
                </div>
                <div class="card-glass p-6">
                    <p class="text-sm font-semibold text-white">Conversation intelligence</p>
                    <p class="mt-3 text-sm leading-7 text-slate-400">Persistent chat history, markdown responses, copy actions, typing effects, and rate limiting.</p>
                </div>
                <div class="card-glass p-6">
                    <p class="text-sm font-semibold text-white">Clean architecture</p>
                    <p class="mt-3 text-sm leading-7 text-slate-400">Thin controllers, focused services, useful repositories, form requests, and testable jobs.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
