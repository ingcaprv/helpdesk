<?php

namespace App\Enums;

enum TicketCategory: int
{
    case Technical = 1;
    case Billing = 2;
    case Support = 3;

    public function label(): string
    {
        return match($this) {
            self::Technical => 'Técnico',
            self::Billing => 'Facturación',
            self::Support => 'Soporte',
        };
    }
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function random(): self
    {
        return collect(self::cases())->random();
    }


}
