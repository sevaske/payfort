<?php

namespace Sevaske\Payfort;

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

    public static function getApiUrl(): string
    {
        if (self::isSandboxMode()) {
            return config('payfort.sandbox_api_url');
        }

        return config('payfort.api_url');
    }

    public static function getLogChannel(): string
    {
        return config('payfort.log_channel');
    }
}
