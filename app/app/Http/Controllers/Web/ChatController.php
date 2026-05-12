<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\StoreConversationRequest;
use App\Http\Requests\Chat\StoreMessageRequest;
use App\Models\Conversation;
use App\Services\Chat\ConversationService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request, ConversationService $conversationService)
    {
        $conversations = $conversationService->recentConversations($request->user());
        $activeConversation = $conversations->first()
            ? $conversationService->renderConversation($conversations->first(), $request->user())
            : null;

        return view('chat.index', [
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
        ]);
    }

    public function store(StoreConversationRequest $request, ConversationService $conversationService)
    {
        $conversation = $conversationService->createConversation(
            $request->user(),
            $request->string('prompt')->toString()
        );

        return redirect()
            ->route('chat.show', $conversation)
            ->with('toast', 'Conversation created. Your AI response is being generated.');
    }

    public function show(Conversation $conversation, Request $request, ConversationService $conversationService)
    {
        $activeConversation = $conversationService->renderConversation($conversation, $request->user());

        return view('chat.index', [
            'conversations' => $conversationService->recentConversations($request->user()),
            'activeConversation' => $activeConversation,
        ]);
    }

    public function send(
        Conversation $conversation,
        StoreMessageRequest $request,
        ConversationService $conversationService
    ) {
        $conversationService->sendMessage(
            $conversation,
            $request->user(),
            $request->string('prompt')->toString()
        );

        return redirect()
            ->route('chat.show', $conversation)
            ->with('toast', 'Message queued. The assistant is preparing a reply.');
    }
}
