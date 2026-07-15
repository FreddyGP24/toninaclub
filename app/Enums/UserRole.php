<?php

namespace App\Enums;

enum UserRole: string
{
    case CLIENT = 'cliente';
    case OWNER = 'propietario';
    case ADMIN = 'administrador';

    public function label(): string
    {
        return match ($this) {
            self::CLIENT => 'Cliente',
            self::OWNER => 'Propietario',
            self::ADMIN => 'Administrador',
        };
    }
}
