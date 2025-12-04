<?php

namespace App\Enums;

enum Currency:string
{
    case MXN = "mxn";
    case USD = "usd";

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => ucfirst($case->value)],
            self::cases()
        );
    }
}
