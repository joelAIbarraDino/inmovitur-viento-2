<?php

namespace App\Enums;

enum LegalPersonality:string
{
    case FISICA = 'Fisica';
    case MORAL = 'Moral';

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => ucfirst($case->value)],
            self::cases()
        );
    }
}
