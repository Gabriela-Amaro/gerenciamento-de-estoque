<?php

namespace App\Enum;

enum FuncionarioCargo: int 
{
    case ADMIN = 1;
    case VENDEDOR = 2;
    case GERENTE = 3;

    public function getDescription(): string
    {
        return match ($this) {
            self::ADMIN => "Administrador",
            self::VENDEDOR => "Vendedor",
            self::GERENTE => "Gerente",
        };
    }
}