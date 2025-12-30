<?php

namespace App\Enums;

enum DocumentType:string
{
    case CONTRATO = 'Contrato';
    
    case ID = 'Identificacion';
    case CURP = 'CURP';
    case FISCO = 'Cedula fiscal';
    case COMPR_DOMIC = 'Comprobante de domicilio';
    case ACTA_NAC = 'Acta de nacimiento';

    case ID_CONYUGE = 'Identificacion de conyuge';
    case CURP_CONYUGE = 'CURP de conyuge';
    case FISCO_CONYUGE = 'Cedula fiscal de conyuge';
    case COMPR_DOMIC_CONYUGE = 'Comprobante de domicilio de conyuge';
    case ACTA_NAC_CONYUGE = 'Acta de nacimiento de conyuge';

    case LEY_ANTILAVADO = 'Formato de ley antilavado';
    case ACT_MATRIM = 'Acta de matrimonio';
    case ESCRITURA_CONST = 'Escritura constitutiva';
    case INTENCION_FIDEICOMISO = 'Intencion de construccion de fideicomiso';
    case CARTA_PODER = 'Poder representante';

    public static function options(): array{
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => ucfirst($case->value)],
            self::cases()
        );
    }
}
