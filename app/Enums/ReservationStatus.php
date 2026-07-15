<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case PENDING = 'pendiente';
    case CONFIRMED = 'confirmada';
    case CANCELLED = 'cancelada';
    case COMPLETED = 'completada';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendiente',
            self::CONFIRMED => 'Confirmada',
            self::CANCELLED => 'Cancelada',
            self::COMPLETED => 'Completada',
        };
    }

    public static function active(): array
    {
        return [self::PENDING, self::CONFIRMED];
    }

    public function canTransitionTo(self $next): bool
    {
        return match ($this) {
            self::PENDING => in_array($next, [self::CONFIRMED, self::CANCELLED], true),
            self::CONFIRMED => in_array($next, [self::COMPLETED, self::CANCELLED], true),
            self::CANCELLED, self::COMPLETED => false,
        };
    }
}
