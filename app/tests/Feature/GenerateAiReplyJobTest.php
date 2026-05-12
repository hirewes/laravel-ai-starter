<?php

namespace Tests\Feature;

use App\Jobs\GenerateAiReplyJob;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\AI\OpenAiChatService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenerateAiReplyJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_completes_pending_assistant_message(): void
    {
        $user = User::factory()->create();
        $user->preference()->create();

        $conversation = Conversation::factory()->for($user)->create();
        Message::factory()->for($conversation)->create([
            'role' => Message::ROLE_USER,
            'content_markdown' => 'Create a launch checklist.',
            'content_html' => null,
        ]);

        $assistantMessage = Message::factory()->for($conversation)->create([
            'role' => Message::ROLE_ASSISTANT,
            'content_markdown' => null,
            'content_html' => null,
            'status' => Message::STATUS_PENDING,
        ]);

        $this->mock(OpenAiChatService::class, function ($mock) {
            $mock->shouldReceive('generateReply')
                ->once()
                ->andReturn([
                    'content' => "## Launch Checklist\n\n- Finalize onboarding\n- Enable queue worker",
                    'model' => 'gpt-4o-mini',
                    'prompt_tokens' => 21,
                    'completion_tokens' => 34,
                    'total_tokens' => 55,
                ]);
        });

        (new GenerateAiReplyJob(
            $conversation->id,
            $assistantMessage->id,
        ))->handle(
            app(OpenAiChatService::class),
            app(\App\Repositories\ConversationRepository::class),
            app(\App\Repositories\MessageRepository::class),
            app(\App\Support\Markdown\MarkdownRenderer::class),
        );

        $assistantMessage->refresh();

        $this->assertSame(Message::STATUS_COMPLETED, $assistantMessage->status);
        $this->assertSame(55, $assistantMessage->total_tokens);
        $this->assertStringContainsString('<h2>', $assistantMessage->content_html);
    }
}
