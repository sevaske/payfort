<?php

namespace Sevaske\Payfort\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Sevaske\Payfort\Contracts\WebhookControllerContract;
use Sevaske\Payfort\Events\PayfortFeedbackReceived;
use Sevaske\Payfort\Events\PayfortNotificationReceived;
use Sevaske\Payfort\Facades\Payfort;

class PayfortWebhookController extends Controller implements WebhookControllerContract
{
    public function feedback(Request $request, string $merchantName = 'default'): JsonResponse
    {
        $merchant = Payfort::merchant($merchantName);
        event(new PayfortFeedbackReceived($merchant, $request->post(), $request->query()));

        return response()->json(['success' => 1]);
    }

    public function notification(Request $request, string $merchantName = 'default'): JsonResponse
    {
        $merchant = Payfort::merchant($merchantName);
        event(new PayfortNotificationReceived($merchant, $request->post(), $request->query()));

        return response()->json(['success' => 1]);
    }
}
