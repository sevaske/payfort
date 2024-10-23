<?php

namespace Sevaske\Payfort\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;
use Sevaske\Payfort\Config;
use Sevaske\Payfort\Merchant;

class PayfortWebhookReceived
{
    use Dispatchable, Queueable;

    public function __construct(public Merchant $merchant, public array $payload, public array $query = [])
    {
        if (Config::isDebugMode()) {
            Log::channel(Config::getLogChannel())->info('Payfort webhook received.', [
                'merchant' => $this->merchant->getName(),
                'payload' => $this->payload,
                'query' => $this->query,
            ]);
        }
    }
}
