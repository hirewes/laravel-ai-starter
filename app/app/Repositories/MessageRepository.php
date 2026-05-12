<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class MessageRepository
{
    public function forConversation(Conversation $conversation): Collection
    {
        return $conversation->messages()->oldest()->get();
    }

    public function createUserMessage(Conversation $conversation, string $content): Message
    {
        return $conversation->messages()->create([
            'role' => Message::ROLE_USER,
            'content_markdown' => $content,
            'status' => Message::STATUS_COMPLETED,
        ]);
    }

    public function createPendingAssistantMessage(Conversation $conversation): Message
    {
        return $conversation->messages()->create([
            'role' => Message::ROLE_ASSISTANT,
            'status' => Message::STATUS_PENDING,
        ]);
    }

    public function completeAssistantMessage(
        Message $message,
        string $markdown,
        string $html,
        int $promptTokens = 0,
        int $completionTokens = 0,
        int $totalTokens = 0,
        array $metadata = [],
    ): Message {
        $message->fill([
            'content_markdown' => $markdown,
            'content_html' => $html,
            'status' => Message::STATUS_COMPLETED,
            'prompt_tokens' => $promptTokens,
            'completion_tokens' => $completionTokens,
            'total_tokens' => $totalTokens,
            'error_message' => null,
            'metadata' => $metadata,
        ])->save();

        return $message->refresh();
    }

    public function failAssistantMessage(Message $message, string $error): Message
    {
        $message->fill([
            'status' => Message::STATUS_FAILED,
            'error_message' => $error,
        ])->save();

        return $message->refresh();
    }

    public function buildOpenAiMessages(Conversation $conversation): array
    {
        return $conversation->messages()
            ->whereNotNull('content_markdown')
            ->oldest()
            ->get(['role', 'content_markdown'])
            ->map(fn (Message $message) => [
                'role' => $message->role,
                'content' => $message->content_markdown,
            ])
            ->all();
    }
}
