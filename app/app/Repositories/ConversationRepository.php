<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ConversationRepository
{
    public function forUser(User $user, int $limit = 20): Collection
    {
        return Conversation::query()
            ->withCount('messages')
            ->whereBelongsTo($user)
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get();
    }

    public function createForUser(User $user, string $title): Conversation
    {
        return $user->conversations()->create([
            'title' => $title,
            'last_message_at' => now(),
        ]);
    }

    public function touch(Conversation $conversation, ?Carbon $timestamp = null): void
    {
        $conversation->forceFill([
            'last_message_at' => $timestamp ?? now(),
        ])->save();
    }

    public function statsForUser(User $user): array
    {
        $conversationCount = Conversation::query()
            ->whereBelongsTo($user)
            ->count();

        $tokensUsed = DB::table('messages')
            ->join('conversations', 'conversations.id', '=', 'messages.conversation_id')
            ->where('conversations.user_id', $user->id)
            ->sum('messages.total_tokens');

        $recentChats = Conversation::query()
            ->whereBelongsTo($user)
            ->latest('last_message_at')
            ->limit(5)
            ->get();

        return [
            'conversation_count' => $conversationCount,
            'tokens_used' => (int) $tokensUsed,
            'recent_chats' => $recentChats,
        ];
    }
}
