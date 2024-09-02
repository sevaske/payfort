<?php

namespace Sevaske\Payfort\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sevaske\Payfort\Payfort
 */
class Payfort extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Sevaske\Payfort\Payfort::class;
    }
}
