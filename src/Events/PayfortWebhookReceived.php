<?php

namespace Sevaske\Payfort\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;
use Sevaske\Payfort\Facades\Payfort;
use Sevaske\Payfort\Merchant;

class PayfortWebhookReceived
{
    use Dispatchable, Queueable;

    public function __construct(protected string $merchantName, protected array $payload) {}

    public function getMerchantName(): string
    {
        return $this->merchantName;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getMerchant(): Merchant
    {
        return Payfort::merchant($this->merchantName);
    }
}
