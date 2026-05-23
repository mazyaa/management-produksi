<?php

namespace App\Enums;

enum StatusProduksi: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';
    case REVISED = 'revised';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::SUBMITTED => 'Submitted',
            self::VERIFIED => 'Verified',
            self::REJECTED => 'Rejected',
            self::REVISED => 'Revised',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'slate',
            self::SUBMITTED => 'blue',
            self::VERIFIED => 'emerald',
            self::REJECTED => 'red',
            self::REVISED => 'amber',
        };
    }
}
