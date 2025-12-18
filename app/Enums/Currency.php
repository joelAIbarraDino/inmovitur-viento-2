<?php

namespace App\Enums;

enum Currency:string
{
    case MXN = "mxn";
    case USD = "usd";

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => strtoupper($case->value)],
            self::cases()
        );
    }
}
