<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case Success = 'DONE';
    case FAILED = 'FAILED';
}
