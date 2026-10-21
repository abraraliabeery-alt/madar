<?php

namespace App\Enums;

enum DiscountMethod: string
{
    case Percentage = 'percentage';
    case Fixed = 'fixed';

    public function label(): string
    {
        return match ($this) {
            self::Percentage => 'نسبة %',
            self::Fixed => 'قيمة ثابتة',
        };
    }
}
