<?php

namespace App\Enums;

enum PaymentStatus:string
{
    case COMPLETED = "completed";
    case PENDING = "pending";

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => strtoupper($case->value)],
            self::cases()
        );
    }
}
