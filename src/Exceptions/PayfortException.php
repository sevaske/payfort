<?php

namespace Sevaske\Payfort\Exceptions;

use Exception;
use Throwable;

class PayfortException extends Exception
{
    public function __construct(
        $message = '',
        $code = 0,
        ?Throwable $previous = null,
        protected array $context = []
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function withContext(array $context): static
    {
        $this->context = array_merge($this->context, $context);

        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
