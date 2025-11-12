<?php

namespace App\Enums;

enum InvoiceType: string
{
    case SALE = 'sale';
    case EXPENSE = 'expense';

    public function label(): string
    {
        return match ($this) {
            self::SALE => 'Satış',
            self::EXPENSE => 'Gider',
        };
    }
}
