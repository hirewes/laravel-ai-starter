<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_conversation_and_token_stats(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->for($user)->create(['title' => 'Product strategy']);
        Message::factory()->for($conversation)->create(['total_tokens' => 55]);
        Message::factory()->for($conversation)->create([
            'role' => Message::ROLE_ASSISTANT,
            'total_tokens' => 80,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('1')
            ->assertSee('135')
            ->assertSee('Product strategy');
    }
}
