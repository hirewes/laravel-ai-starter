<?php

namespace Tests\Feature;

use App\Jobs\GenerateAiReplyJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiConversationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_dashboard_stats_api(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->getJson(route('api.dashboard'))
            ->assertOk()
            ->assertJsonStructure(['data' => ['conversation_count', 'tokens_used', 'recent_chats']]);
    }

    public function test_authenticated_user_can_create_conversation_via_api(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->postJson(route('api.conversations.store'), [
            'prompt' => 'Generate onboarding ideas for a new AI SaaS.',
        ])->assertCreated();

        Queue::assertPushed(GenerateAiReplyJob::class);
    }
}
