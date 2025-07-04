<?php

namespace Sevaske\Payfort\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Sevaske\Payfort\Config;
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
        // todo check
        if (Config::isDebugMode() && $logChannelName = Config::getLogChannel()) {
            Log::channel($logChannelName)->info('Payfort webhook received.', [
                'merchant' => $merchantName,
                'request' => $request->all(),
                'event' => $eventClass,
            ]);
        }

        // extract payload from request
        $payload = $request->except('signature');

        // trigger the event
        event(new $eventClass($merchantName, $payload));

        return response()->json(['success' => 1]);
    }
}
