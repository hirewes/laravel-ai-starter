@extends('layouts.app')

@section('title', 'AI Chat | Laravel AI Starter')

@php
    $initialMessages = $activeConversation
        ? $activeConversation->messages->map(fn ($message) => [
            'id' => $message->id,
            'role' => $message->role,
            'status' => $message->status,
            'content_markdown' => $message->content_markdown,
            'content_html' => $message->content_html,
            'error_message' => $message->error_message,
            'created_at' => optional($message->created_at)->toIso8601String(),
        ])->values()
        : collect();
@endphp

@section('content')
    <div
        x-data="chatPage({
            conversationId: {{ $activeConversation?->id ?? 'null' }},
            messagesEndpoint: @js($activeConversation ? route('api.conversations.messages.index', $activeConversation) : null),
            messages: @js($initialMessages),
        })"
        x-init="init()"
        class="grid gap-6 xl:grid-cols-[320px_minmax(0,1fr)]"
    >
        <aside class="card-glass flex h-[calc(100vh-10rem)] flex-col overflow-hidden">
            <div class="border-b border-white/10 p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-slate-400">AI Chat</p>
                <h1 class="mt-3 text-2xl font-semibold text-white">Conversations</h1>
                <p class="mt-2 text-sm text-slate-400">Create a new thread and let the queue handle the response lifecycle.</p>
            </div>

            <div class="border-b border-white/10 p-6">
                <form method="POST" action="{{ route('chat.store') }}" class="space-y-4">
                    @csrf
                    <textarea
                        name="prompt"
                        rows="4"
                        class="form-input min-h-[120px] resize-none"
                        placeholder="Start a new conversation with a product, engineering, or content prompt..."
                    >{{ old('prompt') }}</textarea>
                    @error('prompt') <p class="text-sm text-rose-300">{{ $message }}</p> @enderror
                    <button type="submit" class="btn-primary w-full justify-center">New conversation</button>
                </form>
            </div>

            <div class="flex-1 space-y-3 overflow-y-auto p-4">
                @forelse ($conversations as $conversation)
                    <a
                        href="{{ route('chat.show', $conversation) }}"
                        class="block rounded-2xl border p-4 transition {{ $activeConversation?->is($conversation) ? 'border-blue-400/40 bg-blue-400/10' : 'border-white/10 bg-white/5 hover:border-white/20 hover:bg-white/10' }}"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="line-clamp-2 text-sm font-medium text-white">{{ $conversation->title }}</p>
                                <p class="mt-2 text-xs text-slate-400">{{ optional($conversation->last_message_at)->diffForHumans() }}</p>
                            </div>
                            @if ($activeConversation?->is($conversation))
                                <span class="rounded-full bg-white px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-950">Active</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-white/10 bg-slate-950/50 p-5 text-sm text-slate-400">
                        No conversations yet. Use the composer above to generate your first AI response.
                    </div>
                @endforelse
            </div>
        </aside>

        <section class="card-glass flex h-[calc(100vh-10rem)] flex-col overflow-hidden">
            @if ($activeConversation)
                <div class="border-b border-white/10 px-6 py-5">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-slate-400">Active thread</p>
                            <h2 class="mt-2 text-2xl font-semibold text-white">{{ $activeConversation->title }}</h2>
                        </div>
                        <span class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-200">
                            Queue-backed responses
                        </span>
                    </div>
                </div>

                <div x-ref="messageContainer" class="flex-1 space-y-5 overflow-y-auto px-6 py-6">
                    <template x-for="message in messages" :key="message.id">
                        <div :class="message.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                            <div class="max-w-3xl">
                                <div class="mb-2 flex items-center gap-3 text-xs uppercase tracking-[0.2em] text-slate-400" :class="message.role === 'user' ? 'justify-end' : 'justify-start'">
                                    <span x-text="message.role === 'user' ? 'You' : 'Assistant'"></span>
                                    <span x-show="message.status === 'pending'">Generating...</span>
                                    <span x-show="message.status === 'failed'" class="text-rose-300">Failed</span>
                                </div>

                                <div :class="message.role === 'user' ? 'chat-bubble-user' : 'chat-bubble-assistant'">
                                    <template x-if="message.status === 'completed' && message.role === 'assistant'">
                                        <div class="prose prose-invert max-w-none" x-html="message.display_html || message.content_html"></div>
                                    </template>
                                    <template x-if="message.role === 'user'">
                                        <p class="whitespace-pre-wrap" x-text="message.content_markdown"></p>
                                    </template>
                                    <template x-if="message.role === 'assistant' && message.status === 'pending'">
                                        <div class="flex items-center gap-3 text-sm text-slate-300">
                                            <span class="inline-flex h-2.5 w-2.5 animate-pulse rounded-full bg-blue-400"></span>
                                            Thinking through the response...
                                        </div>
                                    </template>
                                    <template x-if="message.role === 'assistant' && message.status === 'failed'">
                                        <p class="text-sm text-rose-200" x-text="message.error_message || 'The AI request failed.'"></p>
                                    </template>
                                </div>

                                <div x-show="message.role === 'assistant' && message.status === 'completed'" class="mt-3 flex justify-end">
                                    <button
                                        type="button"
                                        class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-slate-300 transition hover:bg-white/10 hover:text-white"
                                        @click="copyMessage(message)"
                                    >
                                        <span x-text="copiedId === message.id ? 'Copied' : 'Copy response'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="border-t border-white/10 p-6">
                    <form method="POST" action="{{ route('chat.messages.store', $activeConversation) }}" class="space-y-4">
                        @csrf
                        <textarea name="prompt" rows="4" class="form-input min-h-[128px] resize-none" placeholder="Ask for product ideas, architecture help, marketing copy, release notes, or anything else..."></textarea>
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <p class="text-sm text-slate-400">Responses are queued, stored, and revealed with a typing effect after completion.</p>
                            <button type="submit" class="btn-primary">Send message</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="flex h-full flex-col items-center justify-center px-6 text-center">
                    <span class="rounded-full border border-blue-400/20 bg-blue-400/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-blue-200">Empty state</span>
                    <h2 class="mt-5 text-3xl font-semibold text-white">Your first AI conversation starts here.</h2>
                    <p class="mt-4 max-w-xl text-base leading-7 text-slate-300">
                        Use the sidebar composer to create a conversation. The assistant reply will be queued, persisted, and rendered with markdown support.
                    </p>
                </div>
            @endif
        </section>
    </div>
@endsection
