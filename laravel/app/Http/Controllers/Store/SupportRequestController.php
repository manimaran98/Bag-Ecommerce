<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Jobs\SendSupportRequestNotification;
use App\Models\SupportRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupportRequestController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:10000'],
            'guest_name' => [
                Rule::requiredIf(fn () => $request->user() === null),
                'nullable',
                'string',
                'max:100',
            ],
            'guest_email' => [
                Rule::requiredIf(fn () => $request->user() === null),
                'nullable',
                'email',
                'max:100',
            ],
        ]);

        if ($request->user()) {
            $ticket = SupportRequest::query()->create([
                'user_id' => $request->user()->id,
                'body' => $data['body'],
                'status' => 'open',
            ]);
        } else {
            $ticket = SupportRequest::query()->create([
                'guest_name' => $data['guest_name'] ?? null,
                'guest_email' => $data['guest_email'] ?? null,
                'body' => $data['body'],
                'status' => 'open',
            ]);
        }

        SendSupportRequestNotification::dispatch($ticket);

        return response()->json([
            'message' => 'We received your request. Our team will get back to you. Thank you.',
        ]);
    }
}
