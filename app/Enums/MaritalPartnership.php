<?php

namespace App\Enums;

enum MaritalPartnership:string
{
    case NA = "No aplica";
    case SEPARADO = "Separado";
    case CONYUGADO = "Conyugado";

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => ucfirst($case->value)],
            self::cases()
        );
    }
}
