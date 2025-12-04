<?php

namespace App\Enums;

enum Nacionality:string
{
    case MEXICANA = 'Mexicana';
    case ESTADOUNIDENSE = 'Estadounidense';
    case ARGENTINA = 'Argentina';
    case BOLIVIANA = 'Boliviana';
    case BRASILEÑA = 'Brasileña';
    case CHILENA = 'Chilena';
    case COLOMBIANA = 'Colombiana';
    case COSTARRICENSE = 'Costarricense';
    case CUBANA = 'Cubana';
    case DOMINICANA = 'Dominicana';
    case ECUATORIANA = 'Ecuatoriana';
    case SALVADOREÑA = 'Salvadoreña';
    case GUATEMALTECA = 'Guatemalteca';
    case HONDUREÑA = 'Hondureña';
    case NICARAGÜENSE = 'Nicaragüense';
    case PANAMEÑA = 'Panameña';
    case PARAGUAYA = 'Paraguaya';
    case PERUANA = 'Peruana';
    case PUERTORRIQUEÑA = 'Puertorriqueña';
    case URUGUAYA = 'Uruguaya';
    case VENEZOLANA = 'Venezolana';
    case ALEMANA = 'Alemana';
    case AUSTRIACA = 'Austríaca';
    case BELGA = 'Belga';
    case BRITÁNICA = 'Británica';
    case DANESA = 'Danesa';
    case ESPAÑOLA = 'Española';
    case FINLANDESA = 'Finlandesa';
    case FRANCESA = 'Francesa';
    case GRIEGA = 'Griega';
    case HUNGARA = 'Húngara';
    case IRLANDESA = 'Irlandesa';
    case ITALIANA = 'Italiana';
    case NEERLANDESA = 'Neerlandesa';
    case NORUEGA = 'Noruega';
    case POLACA = 'Polaca';
    case PORTUGUESA = 'Portuguesa';
    case RUSA = 'Rusa';
    case SUECA = 'Sueca';
    case SUIZA = 'Suiza';
    case OTRA = 'Otra';

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => ucfirst($case->value)],
            self::cases()
        );
    }
}
