<?php

namespace Sevaske\Payfort\Contracts;

interface HasCredentials
{
    public function getCredentials(): CredentialsContract;
}
