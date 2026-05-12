<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'conversation_id' => Conversation::factory(),
            'role' => Message::ROLE_USER,
            'content_markdown' => fake()->sentence(),
            'content_html' => '<p>'.fake()->sentence().'</p>',
            'status' => Message::STATUS_COMPLETED,
            'prompt_tokens' => 0,
            'completion_tokens' => 0,
            'total_tokens' => 0,
            'error_message' => null,
            'metadata' => null,
        ];
    }
}
