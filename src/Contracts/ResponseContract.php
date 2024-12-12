<?php

namespace Sevaske\Payfort\Contracts;

use Psr\Http\Message\ResponseInterface;

interface ResponseContract
{
    public function getResponse(): ResponseInterface;

    public function getData(): array;
}
