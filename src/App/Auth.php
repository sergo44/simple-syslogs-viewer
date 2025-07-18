<?php

declare(strict_types=1);

namespace App;

class Auth
{
    public static function isEnabled(): bool
    {
        return trim(getenv("SIMPLE_SYSLOG_VIEWER_LOGIN") ?: "") && trim(getenv("SIMPLE_SYSLOG_VIEWER_PASSWORD") ?: "");
    }

    public static function isAuthorized(): bool
    {
        return self::isEnabled() && (($_SESSION['authorized'] ?? false)) === true;
    }

    public static function setAuthorized(bool $authorized): void
    {
        $_SESSION['authorized'] = $authorized;
    }
}