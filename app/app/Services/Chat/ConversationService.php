<?php

namespace App\Services\Chat;

use App\Jobs\GenerateAiReplyJob;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Support\Markdown\MarkdownRenderer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ConversationService
{
    public function __construct(
        private readonly ConversationRepository $conversations,
        private readonly MessageRepository $messages,
        private readonly MarkdownRenderer $markdown,
    ) {
    }

    public function recentConversations(User $user, int $limit = 20): Collection
    {
        return $this->conversations->forUser($user, $limit);
    }

    public function createConversation(User $user, string $prompt): Conversation
    {
        $conversation = $this->conversations->createForUser(
            $user,
            Str::limit(Str::of($prompt)->squish()->toString(), 60, '...')
        );

        $this->messages->createUserMessage($conversation, $prompt);
        $assistantMessage = $this->messages->createPendingAssistantMessage($conversation);

        GenerateAiReplyJob::dispatch($conversation->id, $assistantMessage->id);

        return $conversation->fresh(['messages']);
    }

    public function sendMessage(Conversation $conversation, User $user, string $prompt): Message
    {
        abort_unless($conversation->user_id === $user->id, 403);

        $this->messages->createUserMessage($conversation, $prompt);
        $assistantMessage = $this->messages->createPendingAssistantMessage($conversation);
        $this->conversations->touch($conversation);

        GenerateAiReplyJob::dispatch($conversation->id, $assistantMessage->id);

        return $assistantMessage;
    }

    public function renderConversation(Conversation $conversation, User $user): Conversation
    {
        abort_unless($conversation->user_id === $user->id, 403);

        $conversation->load(['messages' => fn ($query) => $query->oldest()]);

        $conversation->messages->transform(function (Message $message) {
            if (blank($message->content_html) && filled($message->content_markdown)) {
                $message->content_html = $this->markdown->toHtml($message->content_markdown);
            }

            return $message;
        });

        return $conversation;
    }
}
