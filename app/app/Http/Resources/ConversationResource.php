<?php

namespace App\Http\Resources;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $messages = $this->whenLoaded('messages');
        $latestMessage = $this->relationLoaded('messages')
            ? $this->messages->last()
            : $this->messages()->latest()->first();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'last_message_at' => $this->last_message_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'message_count' => $this->whenCounted('messages'),
            'status' => $latestMessage?->status ?? Message::STATUS_COMPLETED,
            'messages' => MessageResource::collection($messages),
            'latest_preview' => $latestMessage?->content_markdown
                ? str($latestMessage->content_markdown)->limit(120)->toString()
                : null,
        ];
    }
}
