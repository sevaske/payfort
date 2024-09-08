<?php

namespace Sevaske\Payfort\Contracts;

interface CredentialsContract
{
    public function getMerchantIdentifier(): string;

    public function getAccessCode(): string;

    public function getShaRequestPhrase(): string;

    public function getShaResponsePhrase(): string;

    public function getShaType(): string;
}
