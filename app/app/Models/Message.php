<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    public const ROLE_ASSISTANT = 'assistant';
    public const ROLE_SYSTEM = 'system';
    public const ROLE_USER = 'user';

    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_PENDING = 'pending';

    protected $fillable = [
        'conversation_id',
        'role',
        'content_markdown',
        'content_html',
        'status',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'error_message',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function isPendingAssistantReply(): bool
    {
        return $this->role === self::ROLE_ASSISTANT && $this->status === self::STATUS_PENDING;
    }
}
