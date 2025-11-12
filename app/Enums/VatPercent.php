<?php

namespace App\Enums;

enum VatPercent: int
{
    case ZERO = 0;
    case ONE = 1;
    case TEN = 10;
    case TWENTY = 20;

    public function label(): string
    {
        return match ($this) {
            self::ZERO   => '%0',
            self::ONE    => '%1',
            self::TEN    => '%10',
            self::TWENTY => '%20',
        };
    }

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->label();
            return $carry;
        }, []);
    }
}
