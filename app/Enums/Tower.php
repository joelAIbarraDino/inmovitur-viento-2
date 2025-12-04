<?php

namespace App\Enums;

enum Tower:string
{
    case TOWER_A = "Torre A";
    case TOWER_B = "Torre B";
    case TOWER_C = "Torre C";

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => ucfirst($case->value)],
            self::cases()
        );
    }
}
