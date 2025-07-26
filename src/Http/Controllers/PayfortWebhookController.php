<?php

namespace Sevaske\Payfort\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Sevaske\Payfort\Contracts\WebhookControllerContract;
use Sevaske\Payfort\Events\PayfortFeedbackReceived;
use Sevaske\Payfort\Events\PayfortNotificationReceived;

class PayfortWebhookController extends Controller implements WebhookControllerContract
{
    public function feedback(Request $request, string $merchantName = 'default'): JsonResponse
    {
        return $this->handleRequest($request, $merchantName, PayfortFeedbackReceived::class);
    }

    public function notification(Request $request, string $merchantName = 'default'): JsonResponse
    {
        return $this->handleRequest($request, $merchantName, PayfortNotificationReceived::class);
    }

    protected function handleRequest(Request $request, string $merchantName, string $eventClass): JsonResponse
    {
        // extract payload from request
        $payload = $request->except('signature');

        // trigger the event
        event(new $eventClass($merchantName, $payload));

        return response()->json(['success' => 1]);
    }
}
