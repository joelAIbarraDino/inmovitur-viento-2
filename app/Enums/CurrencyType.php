<?php

namespace App\Enums;

enum CurrencyType:string
{
    case MXN = "mxn";
    case USD = "usd";

    public function label(): string
    {
        return match ($this) {
            self::MXN => 'mxn',
            self::USD => 'usd'
        };
    }

    public static function options(): array
    {
        return array_map(fn(self $status) => [
            'value' => $status->value,
            'label' => $status->label(),
        ], self::cases());
    }
}
