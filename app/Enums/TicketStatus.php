<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Open = 'open';
    case Closed = 'closed';

    public function label(): string
    {
        return match($this) {
            self::Open => 'Abierto',
            self::Closed => 'Cerrado'
        };
    }
    /*   public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }*/
}
