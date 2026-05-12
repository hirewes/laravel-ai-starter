@extends('layouts.app')

@section('title', 'Dashboard | Laravel AI Starter')

@section('content')
    <div class="space-y-8">
        <section class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="card-glass p-8">
                <span class="inline-flex rounded-full border border-blue-400/20 bg-blue-400/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-blue-200">
                    Control center
                </span>
                <h1 class="mt-5 text-4xl font-semibold text-white">Build, monitor, and iterate on AI conversations from one dashboard.</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300">
                    Track usage, resume recent chats, and manage your application preferences with a starter designed to feel production-ready from day one.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('chat.index') }}" class="btn-primary">Open AI chat</a>
                    <a href="{{ route('settings.edit') }}" class="btn-secondary">Review settings</a>
                </div>
            </div>

            <div class="card-glass p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">Recent chats</p>
                <div class="mt-5 space-y-3">
                    @forelse ($stats['recent_chats'] as $conversation)
                        <a href="{{ route('chat.show', $conversation) }}" class="block rounded-2xl border border-white/10 bg-white/5 p-4 transition hover:border-blue-400/30 hover:bg-white/10">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-medium text-white">{{ $conversation->title }}</p>
                                <span class="text-xs text-slate-400">{{ optional($conversation->last_message_at)->diffForHumans() }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-2xl border border-dashed border-white/10 bg-slate-950/50 p-6 text-sm text-slate-400">
                            No conversations yet. Start a new chat to see activity appear here.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="grid gap-6 md:grid-cols-3">
            <div class="card-glass p-6">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Total conversations</p>
                <p class="mt-4 text-4xl font-semibold text-white">{{ number_format($stats['conversation_count']) }}</p>
            </div>
            <div class="card-glass p-6">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Tokens used</p>
                <p class="mt-4 text-4xl font-semibold text-white">{{ number_format($stats['tokens_used']) }}</p>
            </div>
            <div class="card-glass p-6">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Starter profile</p>
                <p class="mt-4 text-2xl font-semibold text-white">Queue-backed, REST-ready, mobile-first.</p>
            </div>
        </section>
    </div>
@endsection
