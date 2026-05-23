<?php

namespace App\Enums;

enum Severity: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::CRITICAL => 'Critical',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::LOW => 'slate',
            self::MEDIUM => 'blue',
            self::HIGH => 'amber',
            self::CRITICAL => 'red',
        };
    }
}
