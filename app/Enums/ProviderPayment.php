<?php

namespace App\Enums;

enum ProviderPayment:string
{
    case OPENPAY = 'openPay';
    case WISE = 'wise';

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => ucfirst($case->value)],
            self::cases()
        );
    }
}
