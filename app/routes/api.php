<?php

use App\Http\Controllers\Api\V1\ConversationController;
use App\Http\Controllers\Api\V1\ConversationMessageController;
use App\Http\Controllers\Api\V1\DashboardStatsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/dashboard', DashboardStatsController::class)->name('api.dashboard');

    Route::get('/conversations', [ConversationController::class, 'index'])->name('api.conversations.index');
    Route::post('/conversations', [ConversationController::class, 'store'])
        ->middleware('throttle:chat-messages')
        ->name('api.conversations.store');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('api.conversations.show');

    Route::get('/conversations/{conversation}/messages', [ConversationMessageController::class, 'index'])
        ->name('api.conversations.messages.index');
    Route::post('/conversations/{conversation}/messages', [ConversationMessageController::class, 'store'])
        ->middleware('throttle:chat-messages')
        ->name('api.conversations.messages.store');
});
