<?php

namespace App\Helpers;

class MoneyHelper
{
    public static function format(float $number, bool $isCurrency = false, int $decimals = 2, string $decPoint = ',', string $thousandsSep = '.'): string
    {
        $prefix = $isCurrency ? '₺' : '';

        return $prefix . number_format($number, $decimals, $decPoint, $thousandsSep);
    }

    public static function parse(string $number): float
    {
        return floatval(str_replace(['.', ','], ['', '.'], $number));
    }
}
