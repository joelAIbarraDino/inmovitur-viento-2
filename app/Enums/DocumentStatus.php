<?php

namespace App\Enums;

enum DocumentStatus:string
{
    case ACEPTADO = 'aceptado';
    case REVISION = 'revision';
    case RECHAZADO = 'rechazado';
    case PENDIENTE = 'pendiente';
}
