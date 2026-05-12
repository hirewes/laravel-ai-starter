<?php

namespace App\Jobs;

use App\Models\Conversation;
use App\Models\Message;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Services\AI\OpenAiChatService;
use App\Support\Markdown\MarkdownRenderer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateAiReplyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(
        public readonly int $conversationId,
        public readonly int $assistantMessageId,
    ) {
    }

    public function handle(
        OpenAiChatService $openAiChatService,
        ConversationRepository $conversationRepository,
        MessageRepository $messageRepository,
        MarkdownRenderer $markdownRenderer,
    ): void {
        $conversation = Conversation::query()->with('user.preference')->findOrFail($this->conversationId);
        $assistantMessage = Message::query()->findOrFail($this->assistantMessageId);

        try {
            $result = $openAiChatService->generateReply(
                $messageRepository->buildOpenAiMessages($conversation),
                $conversation->user->preference
            );

            $messageRepository->completeAssistantMessage(
                $assistantMessage,
                $result['content'],
                $markdownRenderer->toHtml($result['content']),
                $result['prompt_tokens'],
                $result['completion_tokens'],
                $result['total_tokens'],
                ['model' => $result['model']]
            );

            $conversationRepository->touch($conversation);
        } catch (Throwable $throwable) {
            $messageRepository->failAssistantMessage($assistantMessage, $throwable->getMessage());
            $conversationRepository->touch($conversation);

            throw $throwable;
        }
    }
}
