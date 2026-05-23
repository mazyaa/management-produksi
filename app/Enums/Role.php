<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case OPERATOR = 'operator';
    case LEADER = 'leader';
    case ASSISTANT_MANAGER = 'assistant_manager';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::OPERATOR => 'Operator Produksi',
            self::LEADER => 'Leader',
            self::ASSISTANT_MANAGER => 'Assistant Manager',
        };
    }
}
