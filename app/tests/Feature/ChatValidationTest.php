<?php

namespace Tests\Feature;

use App\Jobs\GenerateAiReplyJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ChatValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_prompt_is_required(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('chat.store'), ['prompt' => ''])
            ->assertSessionHasErrors('prompt');
    }

    public function test_valid_chat_prompt_dispatches_ai_job(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('chat.store'), ['prompt' => 'Help me draft a SaaS launch plan.'])
            ->assertRedirect();

        Queue::assertPushed(GenerateAiReplyJob::class);
        $this->assertDatabaseCount('conversations', 1);
        $this->assertDatabaseCount('messages', 2);
    }
}
