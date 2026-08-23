<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Services\ContactMessageService;
use Illuminate\Http\JsonResponse;

class ContactMessageController extends Controller
{
    public function store(ContactMessageRequest $request, ContactMessageService $service): JsonResponse
    {
        $service->send($request->validated());

        return response()->json([
            'message' => 'Message sent successfully.',
        ]);
    }
}
