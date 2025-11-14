<?php

namespace App\Enums;

enum TowerType:string
{
    case TOWER_A = "Torre A";
    case TOWER_B = "Torre B";
    case TOWER_C = "Torre C";

        public function label(): string
        {
            return match ($this) {
                self::TOWER_A => 'Torre A',
                self::TOWER_B => 'Torre B',
                self::TOWER_C => 'Torre C'
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
