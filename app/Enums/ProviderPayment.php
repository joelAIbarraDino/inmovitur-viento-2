<?php

namespace App\Enums;

enum ProviderPayment:string
{
    case OPENPAY = 'openPay';
    case WISE = 'wise';
}
