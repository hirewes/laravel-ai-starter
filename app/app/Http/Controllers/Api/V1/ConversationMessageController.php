<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Services\Chat\ConversationService;
use Illuminate\Http\Request;

class ConversationMessageController extends Controller
{
    public function index(Conversation $conversation, Request $request, ConversationService $conversationService)
    {
        $conversation = $conversationService->renderConversation($conversation, $request->user());

        return MessageResource::collection($conversation->messages);
    }

    public function store(
        Conversation $conversation,
        StoreMessageRequest $request,
        ConversationService $conversationService
    ) {
        $message = $conversationService->sendMessage(
            $conversation,
            $request->user(),
            $request->string('prompt')->toString()
        );

        return (new MessageResource($message))
            ->response()
            ->setStatusCode(202);
    }
}
