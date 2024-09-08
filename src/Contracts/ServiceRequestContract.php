<?php

namespace Sevaske\Payfort\Contracts;

interface ServiceRequestContract
{
    public function getPreparedRequestData(): array;

    public function getUri(): string;

    public function getMethod(): string;

    public function rules(): array;
}
