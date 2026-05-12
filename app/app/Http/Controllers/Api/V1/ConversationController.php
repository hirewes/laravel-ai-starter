<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\StoreConversationRequest;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Services\Chat\ConversationService;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index(Request $request, ConversationService $conversationService)
    {
        $conversations = $conversationService->recentConversations($request->user());

        return ConversationResource::collection($conversations);
    }

    public function store(StoreConversationRequest $request, ConversationService $conversationService)
    {
        $conversation = $conversationService->createConversation(
            $request->user(),
            $request->string('prompt')->toString()
        );

        return (new ConversationResource(
            $conversationService->renderConversation($conversation, $request->user())
        ))->response()->setStatusCode(201);
    }

    public function show(Conversation $conversation, Request $request, ConversationService $conversationService)
    {
        return new ConversationResource(
            $conversationService->renderConversation($conversation, $request->user())
        );
    }
}
