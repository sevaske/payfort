<?php

namespace Sevaske\Payfort;

use Sevaske\Payfort\Contracts\CredentialsContract;

class Credentials implements CredentialsContract
{
    public function __construct(
        protected string $merchantIdentifier,
        protected string $accessToken,
        protected string $shaRequestPhrase,
        protected string $shaResponsePhrase,
        protected string $shaType = 'sha256',
    ) {}

    public function getMerchantIdentifier(): string
    {
        return $this->merchantIdentifier;
    }

    public function getAccessCode(): string
    {
        return $this->accessToken;
    }

    public function getShaRequestPhrase(): string
    {
        return $this->shaRequestPhrase;
    }

    public function getShaResponsePhrase(): string
    {
        return $this->shaResponsePhrase;
    }

    public function getShaType(): string
    {
        return $this->shaType;
    }
}
