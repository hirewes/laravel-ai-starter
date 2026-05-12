<?php

namespace App\Services\AI;

use App\Models\UserPreference;
use Illuminate\Support\Arr;
use OpenAI\Laravel\Facades\OpenAI;
use RuntimeException;

class OpenAiChatService
{
    public function generateReply(array $messages, ?UserPreference $preference = null): array
    {
        $apiKey = (string) config('services.openai.api_key');

        if (blank($apiKey)) {
            throw new RuntimeException('OpenAI is not configured. Add OPENAI_API_KEY to your environment.');
        }

        $model = $preference?->preferred_model ?: (string) config('services.openai.model', 'gpt-4o-mini');
        $temperature = $preference?->preferred_temperature ?? (float) config('services.openai.temperature', 0.7);

        $response = OpenAI::chat()->create([
            'model' => $model,
            'temperature' => $temperature,
            'messages' => $messages,
        ]);

        $choice = $response->choices[0] ?? null;
        $usage = $response->usage;

        $content = data_get($choice, 'message.content', '');

        if (is_array($content)) {
            $content = collect($content)
                ->map(fn ($item) => Arr::get($item, 'text') ?: Arr::get($item, 'content'))
                ->filter()
                ->implode("\n");
        }

        return [
            'content' => trim((string) $content),
            'model' => (string) ($response->model ?? $model),
            'prompt_tokens' => (int) ($usage->promptTokens ?? 0),
            'completion_tokens' => (int) ($usage->completionTokens ?? 0),
            'total_tokens' => (int) ($usage->totalTokens ?? 0),
        ];
    }
}
