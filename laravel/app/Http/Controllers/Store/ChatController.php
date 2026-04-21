<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\AiChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function message(Request $request, AiChatService $ai): JsonResponse
    {
        if (! config('ai_chat.enabled', true)) {
            return response()->json(['message' => 'Chat is disabled.'], 503);
        }

        if (! $ai->isConfigured()) {
            return response()->json(['message' => 'Chat is not configured.'], 503);
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
            'messages' => ['sometimes', 'array', 'max:40'],
            'messages.*.role' => ['required_with:messages', 'in:user,assistant'],
            'messages.*.content' => ['required_with:messages', 'string', 'max:8000'],
        ]);

        $history = $data['messages'] ?? [];
        $history[] = ['role' => 'user', 'content' => $data['message']];
        $history = array_slice($history, -40);

        $result = $ai->reply($history, $request->user());

        if (! $result->ok || $result->text === null) {
            return response()->json([
                'message' => 'Chat is temporarily unavailable. Please try again or use Help desk.',
            ], 503);
        }

        return response()->json(['reply' => $result->text]);
    }
}
