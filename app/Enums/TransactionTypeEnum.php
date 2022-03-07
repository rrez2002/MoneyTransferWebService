<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case Internal = 'internal';
    case Paya = 'paya';
}
