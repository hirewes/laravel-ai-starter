<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardStatsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'conversation_count' => $this['conversation_count'],
            'tokens_used' => $this['tokens_used'],
            'recent_chats' => ConversationResource::collection($this['recent_chats']),
        ];
    }
}
