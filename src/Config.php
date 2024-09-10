<?php

namespace Sevaske\Payfort;

use Sevaske\Payfort\Enums\PayfortEnvironment;

class Config
{
    public static function getLanguage(): string
    {
        return config('payfort.language');
    }

    public static function isDebugMode(): bool
    {
        return (bool) config('payfort.debug_mode');
    }

    public static function isSandboxMode(): bool
    {
        return (bool) config('payfort.sandbox_mode');
    }

    public static function isValidationRequestsEnabled(): bool
    {
        return (bool) config('payfort.enable_requests_validation');
    }

    public static function getApiUrl(): string
    {
        if (self::isSandboxMode()) {
            return PayfortEnvironment::Sandbox->getApiUrl();
        }

        return PayfortEnvironment::Production->getApiUrl();
    }

    public static function getLogChannel(): string
    {
        return config('payfort.log_channel');
    }
}
