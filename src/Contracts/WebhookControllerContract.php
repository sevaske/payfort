<?php

namespace Sevaske\Payfort\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface WebhookControllerContract
{
    public function feedback(Request $request, string $merchantName = 'default'): JsonResponse;

    public function notification(Request $request, string $merchantName = 'default'): JsonResponse;
}
